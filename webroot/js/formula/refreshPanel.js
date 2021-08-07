export class RefreshPanel extends React.Component {
  render() {
    return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("button", {
      onClick: this.props.onPlayPause
    }, this.props.paused ? /*#__PURE__*/React.createElement("img", {
      src: "img/formula/play.svg",
      width: "30px",
      height: "30px"
    }) : /*#__PURE__*/React.createElement("img", {
      src: "img/formula/pause.svg",
      width: "30px",
      height: "30px"
    })));
  }

}