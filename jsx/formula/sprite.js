export class Sprite extends React.Component {
    render() {
        let width = this.props.width || .8;
        let height = this.props.height || 2;
        let unit = this.props.unit || "%";
        return (
          <img src={this.props.src}
              className={this.props.className}
              width={width + unit} height={height + unit}
              style={
                {
                  left: this.props.x - width / 2 + unit,
                  top: this.props.y - height / 2 + unit,
                  transform: "rotate(" + this.props.angle + "deg)",
                  transformOrigin: this.props.transformOrigin
                }
              }>
          </img>
        );
    }
}
