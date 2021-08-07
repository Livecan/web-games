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
        return cars.every(car =>
            car.fo_damages.reduce(this.carDamageReduceSum, 0) == this.props.totalWP
        )
    }
    
    render() {
        return (
            <React.Fragment>
              <tr>
                <td colSpan="6">
                  <span>
                    {this.props.editable ? "You" : this.props.name}
                  </span>
                  <SetupPlayerReadyButton
                      disabled={!this.props.editable}
                      conditionsMet={
                        this.carDamageConditionsCheck(this.props.cars, this.props.totalWP)
                      }
                      ready={this.props.readyState}
                      onClick={this.props.onPlayerReadyChange}
                  />
                </td>
              </tr>
              {
                this.props.cars.map(car =>
                  <tr key={car.id}>
                    {
                      car.fo_damages.map(damage =>
                        <td key={damage.id} className={"damage " + damageType[damage.type - 1]}>
                            <input id={damage.id} type="number"
                                {   //TODO: refactoring here???
                                  ...(this.props.editable ?
                                    { defaultValue: damage.wear_points, onChange: this.handleChangeDamage} :
                                    { value: damage.wear_points, readOnly: true}
                                  )
                                }
                            />
                        </td>
                      )
                    }
                  </tr>
                )
              }
            </React.Fragment>
        );
    }
}