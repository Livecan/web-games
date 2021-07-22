var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

import { DamagePanel } from './damagePanel.js';

export var MoveDamageSelector = function (_React$Component) {
  _inherits(MoveDamageSelector, _React$Component);

  function MoveDamageSelector() {
    _classCallCheck(this, MoveDamageSelector);

    return _possibleConstructorReturn(this, (MoveDamageSelector.__proto__ || Object.getPrototypeOf(MoveDamageSelector)).apply(this, arguments));
  }

  _createClass(MoveDamageSelector, [{
    key: "render",
    value: function render() {
      var _this2 = this;

      console.log(JSON.stringify(this.props.moveOptions)); //TODO: render the damage table; as props receive ONLY RELEVANT damages with corresponding MoveOptionId
      return React.createElement(
        "table",
        { id: "damage_table_" + this.props.positionId,
          className: "move_option_damage damage_table" },
        React.createElement(
          "tbody",
          null,
          this.props.moveOptions.map(function (moveOption) {
            return React.createElement(
              "tr",
              { key: moveOption.id,
                onClick: function onClick() {
                  return _this2.props.onSelected(moveOption.id);
                } },
              React.createElement(DamagePanel, { damages: moveOption.fo_damages })
            );
          })
        )
      );
    }
  }]);

  return MoveDamageSelector;
}(React.Component);