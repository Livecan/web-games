import { DamagePanel } from './damagePanel.js';
export class MoveDamageSelector extends React.Component {
  render() {
    console.log(JSON.stringify(this.props.moveOptions)); //TODO: render the damage table; as props receive ONLY RELEVANT damages with corresponding MoveOptionId

    return /*#__PURE__*/React.createElement("table", {
      id: "damage_table_" + this.props.positionId,
      className: "move_option_damage damage_table"
    }, /*#__PURE__*/React.createElement("tbody", null, this.props.moveOptions.map(moveOption => /*#__PURE__*/React.createElement("tr", {
      key: moveOption.id,
      onClick: () => this.props.onSelected(moveOption.id)
    }, /*#__PURE__*/React.createElement(DamagePanel, {
      damages: moveOption.fo_damages
    })))));
  }

}