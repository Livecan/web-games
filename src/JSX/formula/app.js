/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Board extends React.Component {
    constructor(props) {
        super(props);
        this.update = this.update.bind(this);
        this.updateState = this.updateState.bind(this);
        this.update();
    }
    
    updateState(dataX) {
        alert(Object.keys(this));
        alert(Object.keys(dataX));
        
    }
    
    update() {
        $.getJSON('/formula/getBoardUpdateJson/' + this.props.gameId, this.updateState);
    }
    
    render() {
        return (
          <div id="board_parent" style={{overflow: "auto"}}>
            <div id="board">
              <TrackImage src="/img/formula/daytona.jpg"></TrackImage>
            </div>
          </div>
        );
    }
}

class TrackImage extends React.Component {
    render() {
        return (
          <img src={this.props.src} />
        );
    }
}

ReactDOM.render(<Board gameId="153"><i>Children </i>test2<b>!</b></Board>, document.getElementById('root'));

