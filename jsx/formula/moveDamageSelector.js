import { DamagePanel } from './damagePanel.js';

export class MoveDamageSelector extends React.Component {
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
