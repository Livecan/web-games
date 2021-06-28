/* 
 * Formula Game React App
 */

class Board extends React.Component {
    constructor(props) {
        super(props);
        this.state = {boardZoom: 0};
        this.update = this.update.bind(this);
        this.updateGameData = this.updateGameData.bind(this);
        this.update();  //TODO: run this after the document loaded
        this.changeRefresh = this.changeRefresh.bind(this);
        this.chooseGear = this.chooseGear.bind(this);
        this.showDamageOptions = this.showDamageOptions.bind(this);
    }
    
    zooms = ["100%", "150%", "200%", "250%", "300%"];
    
    changeRefresh() {
        if (this.state.refresher != null) {
            clearInterval(this.state.refresher);
            this.setState({refresher: null});
        } else {
            this.setState({refresher: setInterval(this.update, 1000)});
        }
    }
    
    updateBoardZoom(zoom) {
        if (zoom > 0) {
            this.state.boardZoom = Math.min(this.state.boardZoom + 1, this.zooms.length - 1);
        }
        if (zoom < 0) {
            this.state.boardZoom = Math.max(this.state.boardZoom - 1, 0);
        }
        this.setState(this.state);
    }
    
    updateGameData(data) {
        if (data.has_updated) {
            this.setState({
                gameState: data.game_state_id,
                trackDebris: data.fo_debris,
                cars: data.fo_cars.map((car, index) =>
                    {
                        car.index = index;
                        return car;
                    }
                ),
                users: data.users,
                logs: data.fo_logs, //TODO: refactor/use it in a nice UI element
                actions: data.actions,
                modified: data.modified,
            });
        }
    }
    
    chooseGear(gear) {
        $.post('/formula/chooseGear/' + this.props.id,
            { _csrfToken: csrfToken, game_id: this.props.id, gear: gear },
            this.update,
            "json");
        console.log("chooseGear(" + gear + ")");
    }
    
    update() {
        $.getJSON('/formula/getBoardUpdateJson/' + this.props.id, this.updateGameData);
    }
    
    showDamageOptions(positionId) {
        actions = this.state.actions;
        actions.selectedPosition = positionId;
        this.setState({actions: actions});
    }
    
    render() {
        return (
          <div id="board_parent">
            <div className="overflow_helper">
              <div id="board" style={{width: this.zooms[this.state.boardZoom]}}>
                <TrackImage src={this.props.gameBoard}></TrackImage>
                {console.log(JSON.stringify(this.state.actions))}
                {this.state.actions != undefined && this.state.actions.type == "choose_move" &&
                  <AvailableMovesSelectorOverlay
                    availableMoves={this.state.actions.available_moves}
                    positions={this.props.positions}
                    onMovePositionSelected={this.showDamageOptions}/>
                }
                <TrackCars cars={(this.state.cars || []).filter(car => car.fo_position_id != null)}
                  positions={this.props.positions} />
                <TrackDebris debris={this.state.trackDebris || []} positions={this.props.positions} />
              </div>
            </div>
            <SlidePanelStack className="slide_panel_stack_top">
              <SlidePanel showText="zoom">
                <ZoomPanel onRefresh={this.update}
                  onZoomOut={this.updateBoardZoom.bind(this, -1)}
                  onZoomIn={this.updateBoardZoom.bind(this, 1)}
                  />
              </SlidePanel>
              <SlidePanel>
                <RefreshPanel paused={this.state.refresher == null}
                  onPlayPause={this.changeRefresh} />
              </SlidePanel>
            </SlidePanelStack>
            <SlidePanelStack className="slide_panel_stack_bottom">
              <SlidePanel showText="cars stats">
                <CarDamagePanel cars={this.state.cars || []} users={this.state.users} />
              </SlidePanel>
              {this.state.actions != undefined && this.state.actions.type == "choose_gear" &&
                <SlidePanel>
                  <GearChoicePanel current={this.state.actions.current_gear}
                    available={this.state.actions.available_gears}
                    onChooseGear={this.chooseGear} />
                </SlidePanel> 
              }
              {this.state.actions != undefined && this.state.actions.selectedPosition != null &&
                <SlidePanel>
                  <MoveDamageSelector positionId={this.state.actions.selectedPosition}
                    moveOptions={
                      this.state.actions.available_moves.filter(move =>
                        move.fo_position_id == this.state.actions.selectedPosition)} />
                </SlidePanel>
              }
            </SlidePanelStack>
          </div>
        );
    }
}

class MoveDamageSelector extends React.Component {
    render() {
        alert(JSON.stringify(this.props.moveOptions))//TODO: render the damage table; as props receive ONLY RELEVANT damages with corresponding MoveOptionId
        return (
          <table id={"damage_table_" + this.props.positionId}
              className="move_option_damage damage_table">
            <tbody>
            {this.props.moveOptions.map(moveOption =>
              <tr key={moveOption.fo_move_option_id}>
                <DamagePanel damages={moveOption.fo_damages} />
              </tr>
            )}
            </tbody>
          </table>
        );
    }
}

class AvailableMovesSelectorOverlay extends React.Component {
    render() {
        availableMovesPositionIds = Array.from(new Set(this.props.availableMoves.map(move => move.fo_position_id)));
        return (
          <svg id="formula_board" className="board__svg">
            {availableMovesPositionIds.map(positionId =>
              <circle key={positionId}
                id={"move_position_" + positionId} className="move_option"
                cx={this.props.positions[positionId].x / 1000 + "%"}
                cy={this.props.positions[positionId].y / 1000 + "%"}
                r=".8%" fill="purple"
                onClick={() => this.props.onMovePositionSelected(positionId)} />
            )}
          </svg>
        );
    }
}

class GearSelector extends React.Component {
    constructor(props) {
        super(props);
        this.handleMouseEnter = this.handleMouseEnter.bind(this);
        this.handleMouseLeave = this.handleMouseLeave.bind(this);
        this.handleMouseClick = this.handleMouseClick.bind(this);
        this.state = {};
    }
    
    gearPositions = [{x: 191, y: 144}, {x: 191, y: 457}, {x: 300, y: 144},
        {x: 300, y: 457}, {x: 412, y: 144}, {x: 412, y: 457}]
    
    handleMouseEnter() {
        this.setState({hover: true});
    }
    
    handleMouseLeave() {
        this.setState({hover: false});
    }
    
    handleMouseClick() {
        typeof this.props.onClick == "function" && this.props.onClick();
    }
    
    render() {
        return (
          <circle className="gear_select"
            cx={this.gearPositions[this.props.gear - 1].x}
            cy={this.gearPositions[this.props.gear - 1].y}
            r="50" fillOpacity="0"
            strokeWidth={this.state.hover ? "20" : "10"}
            stroke={this.props.color}
            onMouseEnter={this.handleMouseEnter}
            onMouseLeave={this.handleMouseLeave}
            onClick={this.handleMouseClick} />
        );
    }
}

class GearChoicePanel extends React.Component {    
    render() {
        console.log(JSON.stringify(this.props.available.filter(gear => gear != this.props.current)));
        return (
            <div style={{position: "relative"}}>
              <img src="/img/formula/gearbox.svg" className="gear_choice"
                width="100px" height="100px"/>
              <svg viewBox="0 0 600 600" width="100" height="100"
                style={{position: "absolute", top: 0, left: 0}}>
                  {this.props.available.map(gear =>
                    <GearSelector key={gear} gear={gear}
                      color={(gear == this.props.current) ? "green" : "red"}
                      onClick={() => this.props.onChooseGear(gear)} />
                  )}
              </svg>
            </div>
        );
    }
}

var damageTypeClass = ["tires", "gearbox", "brakes", "engine", "chassis", "shocks"];

class DamagePanel extends React.Component {
    render() {
        return (
          <React.Fragment>
          {this.props.damages.map(damage =>
            <td key={damage.type}
              className={"damage " + damageTypeClass[damage.type - 1]}>
                {damage.wear_points}
            </td>
          )}
          </React.Fragment>
        );
    }
}

class CarDamagePanel extends React.Component {
    render() {
        return (
            <table id="car_stats_table" className="damage_table">
              <tbody>
                {this.props.cars.map(car =>
                  <tr key={car.index}>
                    <td>
                      <Sprite src={"/img/formula/cars/" + carSprites[car.index]}
                        className="car_img" key={car.index}
                        width="20" height="50" unit="px" />
                    </td>
                    <td>
                      {this.props.users.find(user => user.id == car.user_id).name}
                    </td>
                    <DamagePanel damages={car.fo_damages} />
                  </tr>
                )}
              </tbody>
            </table>
        )
    }
}

class RefreshPanel extends React.Component {
    render() {
        return (
            <React.Fragment>
              <button onClick={this.props.onPlayPause}>
                {this.props.paused ? "resume" : "pause"}
              </button>
            </React.Fragment>
        );
    }
}

class ZoomPanel extends React.Component {
    constructor(props) {
        super(props);
    }
    
    render() {
        return (
            <React.Fragment>
              <button onClick={this.props.onRefresh}>Refresh</button>
              <button onClick={this.props.onZoomIn}>+</button>
              <button onClick={this.props.onZoomOut}>-</button>
            </React.Fragment>
        );
    }
}

class SlidePanelStack extends React.Component {
    render() {
        return (
            <div className={"slide_panel_stack " + this.props.className}>
                {this.props.children}
            </div>
        );
    }
}

class SlidePanel extends React.Component {
    constructor(props) {
        super(props);
        this.state = {visible: true};
        this.toggleHide = this.toggleHide.bind(this);
        //this.onToggleHide = props.onToggleHide || (arg => {});
    }
    
    toggleHide() {
        this.state.visible = !this.state.visible;
        //this.onToggleHide(this.state.visible);
        this.setState(this.state);
    }
    
    render() {
        return (
          <div className="slide_panel">
            <div className={"slide_panel__content" + (this.state.visible ? "" : " hidden")}>
              {this.props.children}
            </div>
            <div className="slide_panel__buttons">
              <button className="slide_panel__button" onClick={this.toggleHide}>
                {this.state.visible ? "Hide" : this.props.showText || "Show"}
              </button>
              <span>
                {this.props.modified}
              </span>
            </div>
          </div>
        );
    }
}

class TrackImage extends React.Component {    
    render() {
        return (
          <img className="board__track" src={this.props.src} />
        );
    }
}

let carSprites = ["tdrc01_car01_b.png",
        "tdrc01_car01_e.png",
        "tdrc01_car01_f.png",
        "tdrc01_car03_a.png",
        "tdrc01_car03_c.png",
        "tdrc01_car03_d.png",
        "tdrc01_car04_a.png",
        "tdrc01_car04_d.png",
        "tdrc01_car04_f.png",
        "tdrc01_car07_b.png",
        "tdrc01_car07_d.png",
        "tdrc01_car07_f.png"
    ];
class TrackCars extends React.Component {
    render() {
        if (this.props.cars == null) {
            return null;
        } else {
            return (
              <React.Fragment>
                {this.props.cars.map(car => (
                  <Sprite src={"/img/formula/cars/" + carSprites[car.index]}
                    className="car_img"
                    key={car.index}
                    x={this.props.positions[car.fo_position_id].x / 1000}
                    y={this.props.positions[car.fo_position_id].y / 1000}
                    angle={this.props.positions[car.fo_position_id].angle * 180 / Math.PI - 90}
                  />
                ))}
              </React.Fragment>
            );
        }
    }
}

class TrackDebris extends React.Component {
    render() {
        if (this.props.debris == null) {
            return null;
        } else {
            return (
              <React.Fragment>
                {this.props.debris.map(item => (
                  <Sprite src={"/img/formula/track-objects/oil.png"}
                    className="debris_img"
                    key={item.id}
                    x={this.props.positions[item.fo_position_id].x / 1000}
                    y={this.props.positions[item.fo_position_id].y / 1000}
                    angle={this.props.positions[item.fo_position_id].angle * 180 / Math.PI - 90}
                  />
                ))}
              </React.Fragment>
            );
        }
    }
}

class Sprite extends React.Component {
    render() {
        let width = this.props.width || .8;
        let height = this.props.height || 2;
        let unit = this.props.unit || "%";
        return (
          <img src={this.props.src}
              className={this.props.className}
              width={width + unit} height={height + unit}
              style={
                {
                  left: this.props.x - width / 2 + unit,
                  top: this.props.y - height / 2 + unit,
                  transform: "rotate(" + this.props.angle + "deg)",
                  transformOrigin: this.props.transformOrigin
                }
              }>
          </img>
        );
    }
}

ReactDOM.render(<Board id={id} gameBoard={gameBoard} positions={positions} />, document.getElementById('root'));

