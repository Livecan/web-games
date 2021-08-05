var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

export var SetupGameParamsPanel = function (_React$Component) {
  _inherits(SetupGameParamsPanel, _React$Component);

  function SetupGameParamsPanel(props) {
    _classCallCheck(this, SetupGameParamsPanel);

    var _this = _possibleConstructorReturn(this, (SetupGameParamsPanel.__proto__ || Object.getPrototypeOf(SetupGameParamsPanel)).call(this, props));

    _this.handleTrackChoiceChange = _this.handleTrackChoiceChange.bind(_this);
    _this.handleCarsPerPlayerChange = _this.handleCarsPerPlayerChange.bind(_this);
    _this.handleWPAvailableChange = _this.handleWPAvailableChange.bind(_this);
    _this.handleLapsChange = _this.handleLapsChange.bind(_this);
    return _this;
  }

  _createClass(SetupGameParamsPanel, [{
    key: "handleTrackChoiceChange",
    value: function handleTrackChoiceChange(event) {
      this.props.onUpdate({ fo_track_id: event.target.value });
    }
  }, {
    key: "handleCarsPerPlayerChange",
    value: function handleCarsPerPlayerChange(event) {
      this.props.onUpdate({ cars_per_player: event.target.value });
    }
  }, {
    key: "handleWPAvailableChange",
    value: function handleWPAvailableChange(event) {
      this.props.onUpdate({ wear_points: event.target.value });
    }
  }, {
    key: "handleLapsChange",
    value: function handleLapsChange(event) {
      this.props.onUpdate({ laps: event.target.value });
    }
  }, {
    key: "render",
    value: function render() {
      return React.createElement(
        "table",
        null,
        React.createElement(
          "tbody",
          null,
          React.createElement(
            "tr",
            null,
            React.createElement(
              "td",
              null,
              React.createElement(
                "label",
                { htmlFor: "track-choice" },
                "Track"
              )
            ),
            React.createElement(
              "td",
              null,
              React.createElement(
                "select",
                { name: "track-choice", id: "track-choice",
                  onChange: this.handleTrackChoiceChange,
                  defaultValue: this.props.game.fo_game.fo_track_id,
                  value: !this.props.editable ? this.props.game.fo_game.fo_track_id : null,
                  disabled: !this.props.editable },
                React.createElement(
                  "option",
                  { value: "1" },
                  "Monaco"
                ),
                React.createElement(
                  "option",
                  { value: "2" },
                  "Daytona"
                ),
                "//TODO: load these option from the server"
              )
            )
          ),
          React.createElement(
            "tr",
            null,
            React.createElement(
              "td",
              { colSpan: "2" },
              React.createElement("img", { src: "img/formula/" + this.props.game.fo_game.fo_track.game_plan,
                alt: this.props.game.fo_game.fo_track.game_plan })
            )
          ),
          React.createElement(
            "tr",
            null,
            React.createElement(
              "td",
              null,
              React.createElement(
                "label",
                { htmlFor: "cars-per-player" },
                "Cars per player"
              )
            ),
            React.createElement(
              "td",
              null,
              React.createElement("input", { type: "number", id: "cars-per-player",
                name: "cars-per-player", min: "1",
                defaultValue: this.props.game.fo_game.cars_per_player,
                value: !this.props.editable ? this.props.game.fo_game.cars_per_player : null,
                onChange: this.handleCarsPerPlayerChange,
                disabled: !this.props.editable })
            )
          ),
          React.createElement(
            "tr",
            null,
            React.createElement(
              "td",
              null,
              React.createElement(
                "label",
                { htmlFor: "wear-points-available" },
                "WP"
              )
            ),
            React.createElement(
              "td",
              null,
              React.createElement("input", { type: "number", id: "wear-points-available",
                name: "wear-points-available", min: "6",
                defaultValue: this.props.game.fo_game.wear_points,
                value: !this.props.editable ? this.props.game.fo_game.wear_points : null,
                onChange: this.handleWPAvailableChange,
                disabled: !this.props.editable })
            )
          ),
          React.createElement(
            "tr",
            null,
            React.createElement(
              "td",
              null,
              React.createElement(
                "label",
                { htmlFor: "laps" },
                "Laps"
              )
            ),
            React.createElement(
              "td",
              null,
              React.createElement("input", { type: "number", id: "laps", name: "laps", min: "1",
                defaultValue: this.props.game.fo_game.laps,
                value: !this.props.editable ? this.props.game.fo_game.laps : null,
                onChange: this.handleLapsChange,
                disabled: !this.props.editable })
            )
          ),
          this.props.editable && React.createElement(
            "tr",
            null,
            React.createElement(
              "td",
              { colSpan: "2" },
              React.createElement(
                "button",
                null,
                "Start"
              )
            )
          )
        )
      );
    }
  }]);

  return SetupGameParamsPanel;
}(React.Component);