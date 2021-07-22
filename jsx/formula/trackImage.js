export class TrackImage extends React.Component {    
    render() {
        return (
          <img className="board__track" src={this.props.src} />
        );
    }
}