import { Sprite } from './sprite.js';
export class TrackDebris extends React.Component {
  render() {
    if (this.props.debris == null) {
      return null;
    } else {
      return /*#__PURE__*/React.createElement(React.Fragment, null, this.props.debris.map(item => /*#__PURE__*/React.createElement(Sprite, {
        src: "img/formula/track-objects/oil.png",
        className: "debris_img",
        key: item.id,
        x: this.props.positions[item.fo_position_id].x / 1000,
        y: this.props.positions[item.fo_position_id].y / 1000,
        width: "2%",
        height: "1.3%",
        angle: this.props.positions[item.fo_position_id].angle * 180 / Math.PI
      })));
    }
  }

}