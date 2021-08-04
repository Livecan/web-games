var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

import { damageType } from './variables.js';

export var SetupPlayersCarsPanel = function (_React$Component) {
  _inherits(SetupPlayersCarsPanel, _React$Component);

  function SetupPlayersCarsPanel() {
    _classCallCheck(this, SetupPlayersCarsPanel);

    return _possibleConstructorReturn(this, (SetupPlayersCarsPanel.__proto__ || Object.getPrototypeOf(SetupPlayersCarsPanel)).apply(this, arguments));
  }

  _createClass(SetupPlayersCarsPanel, [{
    key: "render",
    value: function render() {
      return React.createElement(
        "table",
        null,
        React.createElement(
          "tbody",
          null,
          this.props.users.map(function (user) {
            return React.createElement(
              React.Fragment,
              { key: user.id },
              React.createElement(
                "tr",
                null,
                React.createElement(
                  "td",
                  { colSpan: "8" },
                  React.createElement(
                    "span",
                    null,
                    user.name
                  )
                )
              ),
              user.fo_cars.map(function (car) {
                return React.createElement(
                  "tr",
                  { key: car.id },
                  React.createElement("td", null),
                  car.fo_damages.map(function (damage) {
                    return React.createElement(
                      "td",
                      { key: damage.id, className: "damage " + damageType[damage.type - 1] },
                      React.createElement("input", { id: "damage" + damage.id, type: "number", defaultValue: damage.wear_points })
                    );
                  })
                );
              })
            );
          })
        )
      );
    }
  }]);

  return SetupPlayersCarsPanel;
}(React.Component);