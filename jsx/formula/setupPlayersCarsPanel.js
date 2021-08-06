import { SetupPlayerCars } from './setupPlayerCars.js';

export class SetupPlayersCarsPanel extends React.Component {
    constructor(props) {
        super(props);
        this.handlePlayerReadyChange = this.handlePlayerReadyChange.bind(this);
    }
    
    handlePlayerReadyChange(ready) {
        this.props.onPlayerReadyChange(ready);
    }
    
    render() {
        return (
            <table>
              <tbody>
                {
                  this.props.users.map(user =>
                    <SetupPlayerCars key={user.id} name={user.name}
                        readyState={user.ready_state}
                        cars={user.fo_cars} editable={user.editable}
                        totalWP={this.props.totalWP}
                        onDamageChange={this.props.onDamageChange}
                        onPlayerReadyChange={this.handlePlayerReadyChange} />
                  )
                }
              </tbody>
            </table>
        );
    }
}