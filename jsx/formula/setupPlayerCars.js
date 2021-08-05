import { damageType } from './variables.js';

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
    
    render() {        
        return (
            <React.Fragment>
              <tr>
                <td colSpan="5">
                  <span>
                    {this.props.editable ? "You" : this.props.name}
                  </span>
                </td>
                <td>
                  <span>
                    {
                      this.props.cars.every(car =>
                        car.fo_damages.reduce(this.carDamageReduceSum, 0) == this.props.totalWP
                      ) ? "WP OK" : "not ready"
                    }
                  </span>
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