import { Sprite } from './sprite.js';
import { carSprites } from './variables.js';
import { DamagePanel } from './damagePanel.js';

export class CarDamagePanel extends React.Component {
    compare(a, b) {
        let lastOrder = 999;
        if (a.state == b.state) {
            if (a.state == "R" && (a.order ?? lastOrder) < (b.order ?? lastOrder)) {  //both racing - compare order
                return -1;
            }
            if (a.state == "F" && a.ranking < b.ranking) {  //both finished - compare ranking
                return -1;
            }
            if (a.state == "X" && a.id < b.id) {  //if both out, just make sure that there is a given order
                return -1;
            }
            return 1;
        }
        if (a.state == "R") {   //only A is racing
            return -1;
        }
        if (b.state == "R") {   //only B is racing
            return 1;
        }
        if (a.state == "F") {   //A is racing and B retired
            return -1;
        }
        if (b.state == "F") {   //B is racing and A retired
            return 1;
        }
        return 1;
    }

    render() {
        return (
            <table id="car_stats_table" className="damage_table">
              <tbody>
                {this.props.cars
                  .sort(this.compare)
                  .map(car =>
                    <tr key={car.index}>
                      <td>
                        <Sprite src={"img/formula/cars/" + carSprites[car.index]}
                          className="car_img" key={car.id}
                          width="20" height="50" unit="px" />
                      </td>
                      <td>
                        {
                          {
                            'R': //racing - display current gear
                              <Sprite src={"img/formula/gears/" + Math.max(1, car.gear || 1) + ".svg"}
                                className="state"
                                width="20px" height="20px" />,
                            'F': //finished - display ranking
                              <span className="ranking">
                                <Sprite src="img/formula/gears/finish.svg"
                                  className="finish_img"
                                  width="20px" height="20px"
                                />
                                <span>
                                  {car.ranking}
                                </span>
                              </span>,
                            'X': //retired - display X icon
                              <Sprite src="img/formula/gears/out.svg"
                                className="state"
                                width="20px" height="20px" />
                          }[car.state]
                        }
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
