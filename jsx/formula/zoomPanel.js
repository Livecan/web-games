export class ZoomPanel extends React.Component {
    constructor(props) {
        super(props);
    }
    
    render() {
        return (
            <React.Fragment>
              <button onClick={this.props.onRefresh}>
                <img src="img/formula/refresh.svg" width="30px" height="30px" />
              </button>
              {this.props.noZoomIn ? null :
                <button onClick={this.props.onZoomIn}>
                  <img src="img/formula/plus.svg" width="30px" height="30px" />
                </button>
              }
              {this.props.noZoomOut ? null :
                <button onClick={this.props.onZoomOut}>
                  <img src="img/formula/minus.svg" width="30px" height="30px" />
                </button>
              }
            </React.Fragment>
        );
    }
}
