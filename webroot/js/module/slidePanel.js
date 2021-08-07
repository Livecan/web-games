export class SlidePanelStack extends React.Component {
  render() {
    return /*#__PURE__*/React.createElement("div", {
      className: "slide_panel_stack " + this.props.className
    }, this.props.children);
  }

}
export class SlidePanel extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      visible: true
    };
    this.toggleHide = this.toggleHide.bind(this); //this.onToggleHide = props.onToggleHide || (arg => {});
  }

  toggleHide() {
    this.state.visible = !this.state.visible; //this.onToggleHide(this.state.visible);

    this.setState(this.state);
  }

  render() {
    return /*#__PURE__*/React.createElement("div", {
      className: "slide_panel"
    }, /*#__PURE__*/React.createElement("div", {
      className: "slide_panel__content" + (this.state.visible ? "" : " hidden")
    }, this.props.children), /*#__PURE__*/React.createElement("div", {
      className: "slide_panel__buttons"
    }, /*#__PURE__*/React.createElement("button", {
      className: "slide_panel__button",
      onClick: this.toggleHide
    }, this.state.visible ? this.props.hideIcon ? /*#__PURE__*/React.createElement("img", {
      src: this.props.hideIcon,
      width: "30",
      height: "12"
    }) : this.props.hideText || "Hide" : this.props.showIcon ? /*#__PURE__*/React.createElement("img", {
      src: this.props.showIcon,
      width: "30",
      height: "12"
    }) : this.props.showText || "Show"), /*#__PURE__*/React.createElement("span", null, this.props.modified)));
  }

}