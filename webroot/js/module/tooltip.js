export class Tooltip extends React.Component {
  render() {
    //TODO: figure out which params should go to CSS and do styling
    return /*#__PURE__*/React.createElement("div", {
      className: "tooltip",
      style: {
        position: "fixed",
        left: this.props.x,
        top: this.props.y
      }
    }, this.props.children);
  }

}