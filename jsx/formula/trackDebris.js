import { Sprite } from './sprite.js';

export class TrackDebris extends React.Component {
    render() {
        if (this.props.debris == null) {
            return null;
        } else {
            return (
              <React.Fragment>
                {this.props.debris.map(item => (
                  <Sprite src={"/img/formula/track-objects/oil.png"}
                    className="debris_img"
                    key={item.id}
                    x={this.props.positions[item.fo_position_id].x / 1000}
                    y={this.props.positions[item.fo_position_id].y / 1000}
                    angle={this.props.positions[item.fo_position_id].angle * 180 / Math.PI - 90}
                  />
                ))}
              </React.Fragment>
            );
        }
    }
}

