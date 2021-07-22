var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

import { Sprite } from './sprite.js';

export var TrackDebris = function (_React$Component) {
  _inherits(TrackDebris, _React$Component);

  function TrackDebris() {
    _classCallCheck(this, TrackDebris);

    return _possibleConstructorReturn(this, (TrackDebris.__proto__ || Object.getPrototypeOf(TrackDebris)).apply(this, arguments));
  }

  _createClass(TrackDebris, [{
    key: "render",
    value: function render() {
      var _this2 = this;

      if (this.props.debris == null) {
        return null;
      } else {
        return React.createElement(
          React.Fragment,
          null,
          this.props.debris.map(function (item) {
            return React.createElement(Sprite, { src: "/img/formula/track-objects/oil.png",
              className: "debris_img",
              key: item.id,
              x: _this2.props.positions[item.fo_position_id].x / 1000,
              y: _this2.props.positions[item.fo_position_id].y / 1000,
              angle: _this2.props.positions[item.fo_position_id].angle * 180 / Math.PI - 90
            });
          })
        );
      }
    }
  }]);

  return TrackDebris;
}(React.Component);