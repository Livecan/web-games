var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

import { damageType } from './variables.js';

export var DamagePanel = function (_React$Component) {
  _inherits(DamagePanel, _React$Component);

  function DamagePanel() {
    _classCallCheck(this, DamagePanel);

    return _possibleConstructorReturn(this, (DamagePanel.__proto__ || Object.getPrototypeOf(DamagePanel)).apply(this, arguments));
  }

  _createClass(DamagePanel, [{
    key: "render",
    value: function render() {
      return React.createElement(
        React.Fragment,
        null,
        this.props.damages.map(function (damage) {
          return React.createElement(
            "td",
            { key: damage.type,
              className: "damage " + damageType[damage.type - 1] },
            damage.wear_points
          );
        })
      );
    }
  }]);

  return DamagePanel;
}(React.Component);