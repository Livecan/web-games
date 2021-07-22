export class RefreshPanel extends React.Component {
    render() {
        return (
            <React.Fragment>
              <button onClick={this.props.onPlayPause}>
                {this.props.paused ?
                  <img src="/img/formula/play.svg" width="30px" height="30px" /> :
                  <img src="/img/formula/pause.svg" width="30px" height="30px" />
                }
              </button>
            </React.Fragment>
        );
    }
}
