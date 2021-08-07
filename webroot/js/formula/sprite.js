export class Sprite extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      opacity: undefined
    };
    this.setOpacity = this.setOpacity.bind(this);
  }

  setOpacity(opacity) {
    this.setState({
      opacity: opacity
    });
  }

  dimensionRegex = /(?<amount>\d*(?:\.\d*|))(?<unit>[^\d].*|)/;

  render() {
    let widthX = this.props.width.match(this.dimensionRegex);
    let heightX = this.props.height.match(this.dimensionRegex);
    return /*#__PURE__*/React.createElement("img", {
      src: this.props.src,
      className: this.props.className,
      width: this.props.width,
      height: this.props.height,
      style: {
        left: this.props.x - widthX.groups["amount"] / 2 + widthX.groups["unit"],
        top: this.props.y - heightX.groups["amount"] / 2 + heightX.groups["unit"],
        transform: "rotate(" + this.props.angle + "deg)",
        transformOrigin: this.props.transformOrigin,
        opacity: this.state.opacity
      },
      onMouseEnter: this.props.disappearOnMouseOver ? () => this.setOpacity(0) : null,
      onMouseLeave: this.props.disappearOnMouseOver ? () => this.setOpacity(undefined) : null
    });
  }

}