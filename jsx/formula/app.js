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
        this.chooseMoveOption = this.chooseMoveOption.bind(this);
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
                actions: /*{type: "choose_pits",
                    available_points: [
                        {
                            points: 6,
                            damage_types: [1, 2, 3, 4, 5, 6],
                        },
                    ],
                    max_points: [
                        {
                            damage_type: 1,
                            max_points: 8,
                        },
                        {
                            damage_type: 2,
                            max_points: 4,
                        },
                        {
                            damage_type: 3,
                            max_points: 3,
                        },
                        {
                            damage_type: 4,
                            max_points: 4,
                        },
                        {
                            damage_type: 5,
                            max_points: 3,
                        },
                        {
                            damage_type: 6,
                            max_points: 3,
                        },
                    ]
                },*/
                        data.actions,
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
        this.setState({selectedPosition: positionId});
    }
    
    chooseMoveOption(moveOptionId) {
        this.setState({selectedPosition: null});
        $.post('/formula/chooseMoveOption/' + this.props.id,
                { _csrfToken: csrfToken, game_id: this.props.id, move_option_id: moveOptionId },
                this.update,
                "json");
        console.log("chooseMoveOption(" + moveOptionId + ")");
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
              <SlidePanel showIcon="/img/formula/downarrow.svg"
                hideIcon="/img/formula/uparrow.svg">
                  <ZoomPanel onRefresh={this.update}
                    noZoomIn={this.state.boardZoom == this.zooms.length - 1}
                    noZoomOut={this.state.boardZoom == 0}
                    onZoomOut={this.updateBoardZoom.bind(this, -1)}
                    onZoomIn={this.updateBoardZoom.bind(this, 1)}
                    />
              </SlidePanel>
              <SlidePanel showIcon="/img/formula/downarrow.svg"
                hideIcon="/img/formula/uparrow.svg">
                  <RefreshPanel paused={this.state.refresher == null}
                    onPlayPause={this.changeRefresh} />
              </SlidePanel>
            </SlidePanelStack>
            <SlidePanelStack className="slide_panel_stack_bottom">
              {this.state.actions != undefined && this.state.actions.type == "choose_gear" &&
                <SlidePanel showIcon="/img/formula/uparrow.svg"
                  hideIcon="/img/formula/downarrow.svg">
                    <GearChoicePanel current={this.state.actions.current_gear}
                      available={this.state.actions.available_gears}
                      onChooseGear={this.chooseGear} />
                </SlidePanel> 
              }
              {this.state.selectedPosition != null &&
                <SlidePanel showIcon="/img/formula/uparrow.svg"
                  hideIcon="/img/formula/downarrow.svg">
                    <MoveDamageSelector positionId={this.state.selectedPosition}
                      onSelected={this.chooseMoveOption}
                      moveOptions={
                        this.state.actions.available_moves.filter(move =>
                          move.fo_position_id == this.state.selectedPosition)} />
                </SlidePanel>
              }
              {this.state.actions != undefined && this.state.actions.type == "choose_pits" &&
                <SlidePanel showIcon="/img/formula/uparrow.svg"
                  hideIcon="/img/formula/downarrow.svg">
                  <PitStopPanel car={this.state.cars.filter(car => car.state == "R").sort(car => car.order)[0]}
                    availablePoints={this.state.actions.available_points}
                    maxPoints={this.state.actions.max_points} />
                </SlidePanel>
              }
              <SlidePanel showText="cars stats"
                hideIcon="/img/formula/downarrow.svg">
                  <CarDamagePanel update={Math.random()}
                    cars={this.state.cars || []} users={this.state.users} />
              </SlidePanel>
            </SlidePanelStack>
          </div>
        );
    }
}

class PitStopPanel extends React.Component {
    constructor(props) {
        super(props);
        this.state = {assignedPoints: {}};
        this.addPoint = this.addPoint.bind(this);
        this.removePoint = this.removePoint.bind(this);
    }
    
    addPoint(damageType) {
        assignedPoints = this.state.assignedPoints;
        console.log(damageType);
        console.log(JSON.stringify(this.props.car.fo_damages.find(damage => damage.type == damageType)));
        assignedPoints[damageType] =
            Math.min(
                this.props.maxPoints.find(
                    maxPoint => maxPoint.damage_type == damageType).max_points -
                this.props.car.fo_damages.find(damage => damage.type == damageType).wear_points,
                (assignedPoints[damageType] || 0) + 1);
        this.setState({assignedPoints: assignedPoints});
    }
    
    removePoint(damageType) {
        assignedPoints = this.state.assignedPoints;
        assignedPoints[damageType] = Math.max(0,(assignedPoints[damageType] || 0) - 1);
        this.setState({assignedPoints: assignedPoints});
    }
    
    render() {
        console.log(JSON.stringify(this.props.availablePoints));
        console.log(JSON.stringify(this.props.maxPoints));
        console.log(JSON.stringify(this.props.car));
        return (
            <table className="damage_table">
              <tbody>
                {this.props.car.fo_damages.map(damage =>
                  <tr key={damage.type}
                      className={"damage " + damageTypeClass[damage.type - 1]}>
                    <td>
                      <button onClick={() => this.removePoint(damage.type)}>-</button>
                    </td>
                    <td>
                      {damage.wear_points +
                      (this.state.assignedPoints[damage.type] > 0 &&
                        " + " + this.state.assignedPoints[damage.type])
                      }
                    </td>
                    <td>
                      <button onClick={() => this.addPoint(damage.type)}>+</button>
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
        );
    }
}

class MoveDamageSelector extends React.Component {
    render() {
        console.log(JSON.stringify(this.props.moveOptions))//TODO: render the damage table; as props receive ONLY RELEVANT damages with corresponding MoveOptionId
        return (
          <table id={"damage_table_" + this.props.positionId}
              className="move_option_damage damage_table">
            <tbody>
            {this.props.moveOptions.map(moveOption =>
              <tr key={moveOption.id}
                onClick={() => this.props.onSelected(moveOption.id)}>
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
    order(car) {
        let value = (car.state == "R" ? -1000 : 0) + (car.order || 100);
        return value;
    }
    
    render() {
        return (
            <table id="car_stats_table" className="damage_table">
              <tbody>
                {this.props.cars
                  .sort((first, second) =>
                    this.order(first) - this.order(second) < 0 ? -1 : 0)
                  .map(car =>
                    <tr key={car.index}>
                      <td>
                        <Sprite src={"/img/formula/cars/" + carSprites[car.index]}
                          className="car_img" key={car.id}
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
                {this.props.paused ?
                  <img src="/img/formula/play.svg" width="30px" height="30px" /> :
                  <img src="/img/formula/pause.svg" width="30px" height="30px" />
                }
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
              <button onClick={this.props.onRefresh}>
                <img src="/img/formula/refresh.svg" width="30px" height="30px" />
              </button>
              {this.props.noZoomIn ? null :
                <button onClick={this.props.onZoomIn}>
                  <img src="/img/formula/plus.svg" width="30px" height="30px" />
                </button>
              }
              {this.props.noZoomOut ? null :
                <button onClick={this.props.onZoomOut}>
                  <img src="/img/formula/minus.svg" width="30px" height="30px" />
                </button>
              }
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
                {this.state.visible ? (
                  this.props.hideIcon ?
                    <img src={this.props.hideIcon} width="30" height="12" /> :
                    this.props.hideText || "Hide"
                ) :
                  this.props.showIcon ?
                    <img src={this.props.showIcon} width="30" height="12" /> :
                    this.props.showText || "Show"
                }
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

let carSprites = ["1a.png",
        "1b.png",
        "2a.png",
        "2b.png",
        "3a.png",
        "3b.png",
        "4a.png",
        "4b.png",
        "5a.png",
        "5b.png",
        "6a.png",
        "6b.png"
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


