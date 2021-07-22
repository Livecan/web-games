var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

export var AvailableMovesSelectorOverlay = function (_React$Component) {
  _inherits(AvailableMovesSelectorOverlay, _React$Component);

  function AvailableMovesSelectorOverlay() {
    _classCallCheck(this, AvailableMovesSelectorOverlay);

    return _possibleConstructorReturn(this, (AvailableMovesSelectorOverlay.__proto__ || Object.getPrototypeOf(AvailableMovesSelectorOverlay)).apply(this, arguments));
  }

  _createClass(AvailableMovesSelectorOverlay, [{
    key: "render",
    value: function render() {
      var _this2 = this;

      var availableMovesPositionIds = Array.from(new Set(this.props.availableMoves.map(function (move) {
        return move.fo_position_id;
      })));
      return React.createElement(
        "svg",
        { id: "formula_board", className: "board__svg" },
        availableMovesPositionIds.map(function (positionId) {
          return React.createElement("circle", { key: positionId,
            id: "move_position_" + positionId, className: "move_option",
            cx: _this2.props.positions[positionId].x / 1000 + "%",
            cy: _this2.props.positions[positionId].y / 1000 + "%",
            r: ".8%", fill: "purple",
            onClick: function onClick() {
              return _this2.props.onMovePositionSelected(positionId);
            } });
        })
      );
    }
  }]);

  return AvailableMovesSelectorOverlay;
}(React.Component);