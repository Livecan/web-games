export class ZoomPanel extends React.Component {
  constructor(props) {
    super(props);
  }

  render() {
    return /*#__PURE__*/React.createElement(React.Fragment, null, /*#__PURE__*/React.createElement("button", {
      onClick: this.props.onRefresh
    }, /*#__PURE__*/React.createElement("img", {
      src: "img/formula/refresh.svg",
      width: "30px",
      height: "30px"
    })), this.props.noZoomIn ? null : /*#__PURE__*/React.createElement("button", {
      onClick: this.props.onZoomIn
    }, /*#__PURE__*/React.createElement("img", {
      src: "img/formula/plus.svg",
      width: "30px",
      height: "30px"
    })), this.props.noZoomOut ? null : /*#__PURE__*/React.createElement("button", {
      onClick: this.props.onZoomOut
    }, /*#__PURE__*/React.createElement("img", {
      src: "img/formula/minus.svg",
      width: "30px",
      height: "30px"
    })));
  }

}