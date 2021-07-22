import { damageType } from './variables.js';

export class DamagePanel extends React.Component {
    render() {
        return (
          <React.Fragment>
          {this.props.damages.map(damage =>
            <td key={damage.type}
              className={"damage " + damageType[damage.type - 1]}>
                {damage.wear_points}
            </td>
          )}
          </React.Fragment>
        );
    }
}
