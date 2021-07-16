import { damageType } from './variables.js';

export class PitStopPanel extends React.Component {
    constructor(props) {
        super(props);
        this.state = {assignedPoints: {}};
        this.addPoint = this.addPoint.bind(this);
        this.removePoint = this.removePoint.bind(this);
    }
    
    addPoint(damageType) {
        assignedPoints = this.state.assignedPoints;
        console.log(damageType);
        console.log(JSON.stringify(this.props.car.fo_damages.find(damage => damage.type == damageType)));
        assignedPoints[damageType] =
            Math.min(
                this.props.maxPoints.find(
                    maxPoint => maxPoint.damage_type == damageType).max_points -
                this.props.car.fo_damages.find(damage => damage.type == damageType).wear_points,
                (assignedPoints[damageType] || 0) + 1);
        this.setState({assignedPoints: assignedPoints});
    }
    
    removePoint(damageType) {
        assignedPoints = this.state.assignedPoints;
        assignedPoints[damageType] = Math.max(0,(assignedPoints[damageType] || 0) - 1);
        this.setState({assignedPoints: assignedPoints});
    }
    
    render() {
        console.log(JSON.stringify(this.props.availablePoints));
        console.log(JSON.stringify(this.props.maxPoints));
        console.log(JSON.stringify(this.props.car));
        return (
            <table className="damage_table">
              <tbody>
                {this.props.car.fo_damages.map(damage =>
                  <tr key={damage.type}
                      className={"damage " + damageTypeClass[damage.type - 1]}>
                    <td>
                      <button onClick={() => this.removePoint(damage.type)}>-</button>
                    </td>
                    <td>
                      {damage.wear_points +
                      (this.state.assignedPoints[damage.type] > 0 &&
                        " + " + this.state.assignedPoints[damage.type])
                      }
                    </td>
                    <td>
                      <button onClick={() => this.addPoint(damage.type)}>+</button>
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
        );
    }
}