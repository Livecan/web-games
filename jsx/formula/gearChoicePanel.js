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

export class GearChoicePanel extends React.Component {    
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