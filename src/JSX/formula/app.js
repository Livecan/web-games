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
    
    update() {
        $.getJSON('/formula/getBoardUpdateJson/' + this.props.id, this.updateGameData);
    }
    
    render() {
        return (
          <div id="board_parent">
            <div className="overflow_helper">
              <div id="board" style={{width: this.zooms[this.state.boardZoom]}}>
                <TrackImage src={this.props.gameBoard}></TrackImage>
                <svg id="formula_board" className="board__svg"></svg>
                <TrackCars cars={(this.state.cars || []).filter(car => car.fo_position_id != null)}
                  positions={this.props.positions} />
                <TrackDebris debris={this.state.trackDebris || []} positions={this.props.positions} />
              </div>
            </div>
            <SlidePanelStack>
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
              <SlidePanel>
                <CarDamagePanel cars={this.state.cars || []} users={this.state.users} />
              </SlidePanel>
            </SlidePanelStack>
          </div>
        );
    }
}

var damageTypeClass = ["tires", "gearbox", "brakes", "engine", "chassis", "shocks"];

class CarDamagePanel extends React.Component {
    render() {
        return (
            <table id="car_stats_table" className="damage_table">
              {this.props.cars.map(car =>
                <tr>
                  <td>
                    <Sprite src={"/img/formula/cars/" + carSprites[car.index]}
                      className="car_img" key={car.index}
                      width="20" height="50" unit="px" />
                  </td>
                  <td>
                    {this.props.users.find(user => user.id == car.user_id).name}
                  </td>
                  {car.fo_damages.map(damage =>
                    <td className={"damage " + damageTypeClass[damage.type - 1]}>
                      {damage.wear_points}
                    </td>
                  )}
                </tr>
              )}
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
            <div className="slide_panel_stack">
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


