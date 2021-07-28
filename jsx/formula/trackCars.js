import { Sprite } from './sprite.js';
import { carSprites } from './variables.js';

export class TrackCars extends React.Component {
    render() {
        if (this.props.cars == null) {
            return null;
        } else {
            return (
              <React.Fragment>
                {this.props.cars.map(car => (
                  <Sprite src={"/img/formula/cars/" + carSprites[car.index]}
                    className="car_img"
                    key={car.index}
                    width="0.8%"
                    height="2%"
                    x={this.props.positions[car.fo_position_id].x / 1000}
                    y={this.props.positions[car.fo_position_id].y / 1000}
                    angle={this.props.positions[car.fo_position_id].angle * 180 / Math.PI - 90}
                    disappearOnMouseOver={true}
                  />
                ))}
              </React.Fragment>
            );
        }
    }
}
