export class Sprite extends React.Component {
    dimensionRegex = /(?<amount>\d*(?:\.\d*|))(?<unit>[^\d].*|)/;
    
    render() {
        let widthX = this.props.width.match(this.dimensionRegex);
        let heightX = this.props.height.match(this.dimensionRegex);
        
        return (
          <img src={this.props.src}
              className={this.props.className}
              width={this.props.width} height={this.props.height}
              style={
                {
                  left: this.props.x - widthX.groups["amount"] / 2 + widthX.groups["unit"],
                  top: this.props.y - heightX.groups["amount"] / 2 + heightX.groups["unit"],
                  transform: "rotate(" + this.props.angle + "deg)",
                  transformOrigin: this.props.transformOrigin
                }
              }>
          </img>
        );
    }
}
