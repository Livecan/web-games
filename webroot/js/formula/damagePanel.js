import { damageType } from './variables.js';
export class DamagePanel extends React.Component {
  render() {
    return /*#__PURE__*/React.createElement(React.Fragment, null, this.props.damages.map(damage => /*#__PURE__*/React.createElement("td", {
      key: damage.type,
      className: "damage " + damageType[damage.type - 1]
    }, damage.wear_points)));
  }

}