import { SetupPlayerCars } from './setupPlayerCars.js';

export class SetupPlayersCarsPanel extends React.Component {
    render() {
        return (
            <table>
              <tbody>
                {
                  this.props.users.map(user =>
                    <SetupPlayerCars key={user.id} name={user.name}
                        cars={user.fo_cars} editable={user.editable}
                        totalWP={this.props.totalWP}
                        onDamageChange={this.props.onDamageChange} />
                  )
                }
              </tbody>
            </table>
        );
    }
}