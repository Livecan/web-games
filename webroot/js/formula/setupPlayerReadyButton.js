export class SetupPlayerReadyButton extends React.Component {
  constructor(props) {
    super(props);
    this.handleClick = this.handleClick.bind(this);
  }

  handleClick() {
    this.props.onClick(!this.props.ready);
  }

  render() {
    return /*#__PURE__*/React.createElement("button", {
      className: "ready-button",
      disabled: this.props.disabled || !this.props.conditionsMet,
      onClick: this.handleClick
    }, !this.props.conditionsMet && "not ready", this.props.conditionsMet && !this.props.ready && "not ready", this.props.conditionsMet && this.props.ready && "ready");
  }

}