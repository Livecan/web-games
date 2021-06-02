/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ImgSprite extends React.Component {
  render() {
    const positionStyle = {
      left: this.props.left,
      top: this.props.top
    };
    return (
      <img className={this.props.elementClass}
        src={this.props.src}
        style={positionStyle}
        width={this.props.width}
        length={this.props.length}
      />
    );
  }
  
  //TODO: getImgSprite is a candidate for removal
  static getImgSprite(root, elementClass, src, left, top, width, length) {
    return (
      <ImgSprite elementClass={elementClass}
        src={src}
        left={left}
        top={top}
        width={width}
        length={length}
      />
    );
  }
}