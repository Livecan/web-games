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
            onMouseEnter={() => {
                this.handleMouseEnter();
                this.props.onMouseEnter();}}
            onMouseLeave={ () => {
                this.handleMouseLeave();
                this.props.onMouseLeave();}}
            onClick={this.handleMouseClick}>
          </circle>
        );
    }
}

export class GearChoicePanel extends React.Component {
    constructor(props) {
        super(props);
        this.state = {selected: null};
        this.mouseMoveHandler = this.mouseMoveHandler.bind(this);
    }

    gearRolls = [[1, 2], [2, 4], [4, 8], [7, 12], [11, 20], [21, 30]];
    tooltipId = "gearChoice";

    mouseMoveHandler(evt) {
        if (this.state.selected != null) {
            this.props.onDisplayTooltip(
                this.tooltipId,
                evt.nativeEvent.clientX + 10,
                evt.nativeEvent.clientY + 10,
                `Rolls ${this.gearRolls[this.state.selected - 1][0]} - ${this.gearRolls[this.state.selected - 1][1]}`);
        }
        if (this.state.selected == null) {
            this.props.onHideTooltip(this.tooltipId);
        }
    }

    componentWillUnmount() {
        this.props.onHideTooltip(this.tooltipId);
    }

    render() {
        return (
            <div id="gear_choice" style={{position: "relative"}} onMouseMove={this.mouseMoveHandler}>
              <img src="img/formula/gearbox.svg" className="gear_choice"
                width="100px" height="100px"/>
              <svg viewBox="0 0 600 600" width="100" height="100"
                style={{position: "absolute", top: 0, left: 0}}>
                  {this.props.available.map(gear =>
                    <GearSelector key={gear} gear={gear}
                      color={(gear == this.props.current) ? "green" : "red"}
                      onClick={() => this.props.onChooseGear(gear)}
                      onMouseEnter={() => this.setState({selected: gear})}
                      onMouseLeave={() => this.setState({selected: null})}/>
                  )}
              </svg>
            </div>
        );
    }
}
