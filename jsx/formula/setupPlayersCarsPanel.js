import { damageType } from './variables.js';

export class SetupPlayersCarsPanel extends React.Component {
    render() {
        return (
            <table>
              <tbody>
                {
                  this.props.users.map(user =>
                    <React.Fragment key={user.id}>
                      <tr>
                        <td colSpan="8">
                          <span>
                            {user.editable ? "You" : user.name}
                          </span>
                        </td>
                      </tr>
                      {
                        user.fo_cars.map(car =>
                          <tr key={car.id}>
                            <td />
                            {
                              car.fo_damages.map(damage =>
                                <td key={damage.id} className={"damage " + damageType[damage.type - 1]}>
                                  <input id={"damage" + damage.id} type="number" defaultValue={damage.wear_points} />
                                </td>
                              )
                            }
                          </tr>
                        )
                      }
                    </React.Fragment>
                  )
                }
              </tbody>
            </table>
        );
    }
}