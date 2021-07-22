import { Sprite } from './sprite.js';
import { carSprites } from './variables.js';
import { DamagePanel } from './damagePanel.js';

export class CarDamagePanel extends React.Component {
    order(car) {
        let value = (car.state == "R" ? -1000 : 0) + (car.order || 100);
        return value;
    }
    
    render() {
        return (
            <table id="car_stats_table" className="damage_table">
              <tbody>
                {this.props.cars
                  .sort((first, second) =>
                    this.order(first) - this.order(second) < 0 ? -1 : 0)
                  .map(car =>
                    <tr key={car.index}>
                      <td>
                        <Sprite src={"/img/formula/cars/" + carSprites[car.index]}
                          className="car_img" key={car.id}
                          width="20" height="50" unit="px" />
                      </td>
                      <td>
                        <Sprite src={"/img/formula/gears/" + Math.max(1, car.gear) + ".svg"}
                          width="20" height="20" unit="px" />
                      </td>
                      <td>
                        {this.props.users.find(user => user.id == car.user_id).name}
                      </td>
                      <DamagePanel damages={car.fo_damages} />
                    </tr>
                )}
              </tbody>
            </table>
        )
    }
}
