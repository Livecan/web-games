function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

export class SetupGameParamsPanel extends React.Component {
  constructor(props) {
    super(props);
    this.handleTrackChoiceChange = this.handleTrackChoiceChange.bind(this);
    this.handleCarsPerPlayerChange = this.handleCarsPerPlayerChange.bind(this);
    this.handleWPAvailableChange = this.handleWPAvailableChange.bind(this);
    this.handleLapsChange = this.handleLapsChange.bind(this);
  }

  handleTrackChoiceChange(event) {
    this.props.onUpdate({
      fo_track_id: event.target.value
    });
  }

  handleCarsPerPlayerChange(event) {
    this.props.onUpdate({
      cars_per_player: event.target.value
    });
  }

  handleWPAvailableChange(event) {
    this.props.onUpdate({
      wear_points: event.target.value
    });
  }

  handleLapsChange(event) {
    this.props.onUpdate({
      laps: event.target.value
    });
  }

  render() {
    return /*#__PURE__*/React.createElement("table", null, /*#__PURE__*/React.createElement("tbody", null, /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("label", {
      htmlFor: "track-choice"
    }, "Track")), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("select", _extends({
      name: "track-choice",
      id: "track-choice"
    }, this.props.editable ? {
      defaultValue: this.props.game.fo_track_id,
      onChange: this.handleTrackChoiceChange
    } : {
      value: this.props.game.fo_track_id,
      disabled: true
    }), /*#__PURE__*/React.createElement("option", {
      value: "1"
    }, "Monaco"), /*#__PURE__*/React.createElement("option", {
      value: "2"
    }, "Daytona"), "//TODO: load these option from the server"))), /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("td", {
      colSpan: "2"
    }, /*#__PURE__*/React.createElement("img", {
      src: "img/formula/" + this.props.game.fo_track.game_plan,
      alt: this.props.game.fo_track.game_plan
    }))), /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("label", {
      htmlFor: "cars-per-player"
    }, "Cars per player")), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("input", _extends({
      type: "number",
      id: "cars-per-player",
      name: "cars-per-player",
      min: "1"
    }, this.props.editable ? {
      defaultValue: this.props.game.cars_per_player,
      onChange: this.handleCarsPerPlayerChange
    } : {
      value: this.props.game.cars_per_player,
      readOnly: true
    })))), /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("label", {
      htmlFor: "wear-points-available"
    }, "WP")), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("input", _extends({
      type: "number",
      id: "wear-points-available",
      name: "wear-points-available",
      min: "6"
    }, this.props.editable ? {
      defaultValue: this.props.game.wear_points,
      onChange: this.handleWPAvailableChange
    } : {
      value: this.props.game.wear_points,
      readOnly: true
    })))), /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("label", {
      htmlFor: "laps"
    }, "Laps")), /*#__PURE__*/React.createElement("td", null, /*#__PURE__*/React.createElement("input", _extends({
      type: "number",
      id: "laps",
      name: "laps",
      min: "1"
    }, this.props.editable ? {
      defaultValue: this.props.game.laps,
      onChange: this.handleLapsChange
    } : {
      value: this.props.game.laps,
      readOnly: true
    })))), this.props.editable && /*#__PURE__*/React.createElement("tr", null, /*#__PURE__*/React.createElement("td", {
      colSpan: "2"
    }, /*#__PURE__*/React.createElement("button", {
      disabled: !this.props.playersReady,
      onClick: this.props.onStart
    }, "Start")))));
  }

}