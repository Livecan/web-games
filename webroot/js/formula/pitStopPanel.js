import { damageType } from './variables.js';
export class PitStopPanel extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      assignedPoints: {}
    };
    this.addPoint = this.addPoint.bind(this);
    this.removePoint = this.removePoint.bind(this);
  }

  addPoint(damageType) {
    let assignedPoints = this.state.assignedPoints;
    assignedPoints[damageType] = Math.min(this.props.maxPoints.find(maxPoint => maxPoint.damage_type == damageType).wear_points - this.props.car.fo_damages.find(damage => damage.type == damageType).wear_points, (assignedPoints[damageType] || 0) + 1);
    this.setState({
      assignedPoints: assignedPoints
    });
  }

  removePoint(damageType) {
    let assignedPoints = this.state.assignedPoints;
    assignedPoints[damageType] = Math.max(0, (assignedPoints[damageType] || 0) - 1);
    this.setState({
      assignedPoints: assignedPoints
    });
  }

  render() {
    return /*#__PURE__*/React.createElement("table", {
      className: "damage_table"
    }, /*#__PURE__*/React.createElement("tbody", null, this.props.car.fo_damages.map(damage => /*#__PURE__*/React.createElement("tr", {
      key: damage.type,
      className: "damage " + damageType[damage.type - 1]
    }, /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("button", {
      onClick: () => this.removePoint(damage.type)
    }, "-")), /*#__PURE__*/React.createElement("td", null, damage.wear_points + (this.state.assignedPoints[damage.type] > 0 && " + " + this.state.assignedPoints[damage.type])), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("button", {
      onClick: () => this.addPoint(damage.type)
    }, "+"))))));
  }

}