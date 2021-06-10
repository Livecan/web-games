/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Board extends React.Component {
    constructor(props) {
        super(props);
        this.state = {};
        this.update = this.update.bind(this);
        this.updateState = this.updateState.bind(this);
        this.update();  //TODO: run this after document loaded
    }
    
    updateState(data) {
        if (data.has_updated) {
            this.setState((state, props) => {
                return {
                    game_state: data.game_state_id,
                    trackDebris: data.fo_debris.map((debris) =>
                      <TrackDebris key={debris.id}
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
                    logs: data.fo_logs, //TODO: refactor/use it in a nice UI element
                    actions: data.actions,
                    modified: data.modified
                };
            });
        }
        //alert(JSON.stringify(data.fo_cars[0].fo_position_id));
        //alert(JSON.stringify(this.props.positions[data.fo_cars[0].fo_position_id]));
        //alert(Object.keys(data.fo_cars));
    }
    
    update() {
        $.getJSON('/formula/getBoardUpdateJson/' + this.props.id, this.updateState);
    }
    
    render() {
        return (
          <div id="board_parent" style={{overflow: "auto"}}>
            <div id="board">
              <TrackImage src={this.props.gameBoard}></TrackImage>
              <svg id="formula_board" className="board__svg"></svg>
              {this.state.trackCars}
              {this.state.trackDebris}
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
                  left: this.props.x - width / 2 + "%"/*"69.3%"*/,
                  top: this.props.y - height / 2 + "%"/*"42.8%"*/,
                  transform: "rotate(" + this.props.angle/*89.9087*/ + "deg)",
                  transformOrigin: "50% 50%"
                }
              }>
          </img>
        );
    }
}

ReactDOM.render(<Board id={id} gameBoard={gameBoard} positions={positions}><i>Children </i>test2<b>!</b></Board>, document.getElementById('root'));


