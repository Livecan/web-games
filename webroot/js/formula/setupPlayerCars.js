function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

import { damageType } from './variables.js';
import { SetupPlayerReadyButton } from './setupPlayerReadyButton.js';
export class SetupPlayerCars extends React.Component {
  constructor(props) {
    super(props);
    this.handleChangeDamage = this.handleChangeDamage.bind(this);
  }

  carDamageReduceSum(accumulator, currentValue) {
    return accumulator + currentValue.wear_points;
  }

  handleChangeDamage(event) {
    console.log(event.target.id + " " + event.target.value);
    this.props.onDamageChange(event.target.id, event.target.value);
  }

  carDamageConditionsCheck(cars, totalWP) {
    return cars.every(car => car.fo_damages.reduce(this.carDamageReduceSum, 0) == this.props.totalWP);
  }

  render() {
    return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("td", {
      colSpan: "6"
    }, /*#__PURE__*/React.createElement("span", null, this.props.editable ? "You" : this.props.name), /*#__PURE__*/React.createElement(SetupPlayerReadyButton, {
      disabled: !this.props.editable,
      conditionsMet: this.carDamageConditionsCheck(this.props.cars, this.props.totalWP),
      ready: this.props.readyState,
      onClick: this.props.onPlayerReadyChange
    }))), this.props.cars.map(car => /*#__PURE__*/React.createElement("tr", {
      key: car.id
    }, car.fo_damages.map(damage => /*#__PURE__*/React.createElement("td", {
      key: damage.id,
      className: "damage " + damageType[damage.type - 1]
    }, /*#__PURE__*/React.createElement("input", _extends({
      id: damage.id,
      type: "number"
    }, this.props.editable ? {
      defaultValue: damage.wear_points,
      onChange: this.handleChangeDamage
    } : {
      value: damage.wear_points,
      readOnly: true
    })))))));
  }

}