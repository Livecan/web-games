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
    }
    
    zooms = ["100%", "150%", "200%", "250%", "300%"];
    
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
                trackDebris: data.fo_debris.map((debris, index) =>
                    <TrackDebris key={index}
                      x={this.props.positions[debris["fo_position_id"]].x / 1000}
                      y={this.props.positions[debris["fo_position_id"]].y / 1000}
                      angle={this.props.positions[debris["fo_position_id"]].angle * 180 / Math.PI - 90} />
                ),
                trackCars: data.fo_cars.map((car, index) =>
                    <TrackCar key={car.id}
                      img_index ={index}
                      x={this.props.positions[car["fo_position_id"]].x / 1000}
                      y={this.props.positions[car["fo_position_id"]].y / 1000}
                      angle={this.props.positions[car["fo_position_id"]].angle * 180 / Math.PI - 90} />
                ),
                carStats: {},
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
                {this.state.trackCars}
                {this.state.trackDebris}
              </div>
            </div>
            <SlidePanelStack>
              <SlidePanel>
                <ZoomPanel onRefresh={this.update}
                  onZoomIn={this.updateBoardZoom.bind(this, 1)}
                  onZoomOut={this.updateBoardZoom.bind(this, -1)} />
              </SlidePanel>
              <SlidePanel>
                <button>Pause</button>
              </SlidePanel>
            </SlidePanelStack>
          </div>
        );
    }
}

class ZoomPanel extends React.Component {
    constructor(props) {
        super(props);
        this.onZoomIn = props.onZoomIn || (arg => {});
        this.onZoomOut = props.onZoomOut || (arg => {});
        this.onRefresh = props.onRefresh || (arg => {});
    }
    
    render() {
        return (
            <div>
              <button onClick={this.onRefresh}>Refresh</button>
              <button onClick={this.onZoomIn}>+</button>
              <button onClick={this.onZoomOut}>-</button>
            </div>
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
                {this.state.visible ? "Hide" : "Show"}
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

class TrackCar extends React.Component {
    render() {
        return (
          <TrackItem src={"/img/formula/cars/" + carSprites[this.props.img_index]}
              className="car_img"
              x={this.props.x}
              y={this.props.y}
              angle={this.props.angle}
          />
        );
    }
}

class TrackDebris extends React.Component {
    render() {
        return (
          <TrackItem src={"/img/formula/track-objects/oil.png"}
              className="debris_img"
              x={this.props.x}
              y={this.props.y}
              angle={this.props.angle}
          />
        );
    }
}

class TrackItem extends React.Component {
    render() {
        let width = .8;
        let height = 2;
        return (
          <img src={this.props.src}
              className={this.props.className}
              width={width + "%"} height={height + "%"}
              style={
                {
                  left: this.props.x - width / 2 + "%",
                  top: this.props.y - height / 2 + "%",
                  transform: "rotate(" + this.props.angle + "deg)",
                  transformOrigin: "50% 50%"
                }
              }>
          </img>
        );
    }
}

ReactDOM.render(<Board id={id} gameBoard={gameBoard} positions={positions} />, document.getElementById('root'));


