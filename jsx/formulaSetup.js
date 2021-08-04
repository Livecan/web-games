/* 
 * Formula Game Setup React App
 */

import { Tooltip } from './module/tooltip.js';
import { SetupPlayersCarsPanel } from './formula/setupPlayersCarsPanel.js';
import { SetupGameParamsPanel } from './formula/setupGameParamsPanel.js';

class Setup extends React.Component {
    refreshInterval = 2000;
    
    constructor(props) {
        super(props);
        this.state = {};
        this.update = this.update.bind(this);
        this.updateData = this.updateData.bind(this);
        this.update();
    }
    
    updateData(data) {
        console.log(JSON.stringify(data));
        this.setState({data: data});
    }
    
    update() {
        let url = 'formula/getSetupUpdateJson/' + this.props.id;
        $.getJSON(url, this.updateData);
    }
    
    render() {
        if (this.state.data == null) {
            return null;
        }
        else {
            return (
                <div id="setup">
                  <h2 id="game-name">{this.state.data.name}</h2>
                  <div className="row">
                    <div id="player-car-column">
                      <SetupPlayersCarsPanel users={this.state.data.users} />
                    </div>
                    <div id="setup-column">
                      <SetupGameParamsPanel game={this.state.data} />
                    </div>
                  </div>
                </div>
            );
        }
    }
}

ReactDOM.render(<Setup id={id} />, document.getElementById('root'));
