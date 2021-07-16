export class SlidePanelStack extends React.Component {
    render() {
        return (
            <div className={"slide_panel_stack " + this.props.className}>
                {this.props.children}
            </div>
        );
    }
}

export class SlidePanel extends React.Component {
    constructor(props) {
        super(props);
        this.state = {visible: true};
        this.toggleHide = this.toggleHide.bind(this);
        //this.onToggleHide = props.onToggleHide || (arg => {});
    }
    
    toggleHide() {
        this.state.visible = !this.state.visible;
        //this.onToggleHide(this.state.visible);
        this.setState(this.state);
    }
    
    render() {
        return (
          <div className="slide_panel">
            <div className={"slide_panel__content" + (this.state.visible ? "" : " hidden")}>
              {this.props.children}
            </div>
            <div className="slide_panel__buttons">
              <button className="slide_panel__button" onClick={this.toggleHide}>
                {this.state.visible ? (
                  this.props.hideIcon ?
                    <img src={this.props.hideIcon} width="30" height="12" /> :
                    this.props.hideText || "Hide"
                ) :
                  this.props.showIcon ?
                    <img src={this.props.showIcon} width="30" height="12" /> :
                    this.props.showText || "Show"
                }
              </button>
              <span>
                {this.props.modified}
              </span>
            </div>
          </div>
        );
    }
}