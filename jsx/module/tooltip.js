export class Tooltip extends React.Component {
    render() {  //TODO: figure out which params should go to CSS and do styling
        return (
          <span style={{
            position: "fixed",
            display: "inline-block",
            left: this.props.x,
            top: this.props.y,
            width: 100,
            backgroundColor: "white",
            zIndex: 1000000,}}>
            {this.props.text}
          </span>
        );
    }
}
