/* 
 * Formula Game Setup React App
 */

import { Tooltip } from './module/tooltip.js';
import { SetupPlayersCarsPanel } from './formula/setupPlayersCarsPanel.js';
import { SetupGameParamsPanel } from './formula/setupGameParamsPanel.js';

class Setup extends React.Component {
    gameParams = {};
    gameParamsTimeout;
    gameParamsTimeoutMiliseconds = 2000;
    refreshIntervalMiliseconds = 2000;
    
    constructor(props) {
        super(props);
        this.state = {};
        this.update = this.update.bind(this);
        this.updateData = this.updateData.bind(this);
        this.updateGameParams = this.updateGameParams.bind(this);
        this.sendGameParams = this.sendGameParams.bind(this);
        this.update();
        setInterval(this.update, this.refreshIntervalMiliseconds);
    }
    
    updateData(data) {
        console.log(JSON.stringify(data));
        let state = {
            name: data.name,
            users: data.users
        };
        let game = data;
        delete game.users;
        if (this.state.game == null || !this.state.game.editable) {
            state.game = game;
        }
        this.setState(state);
    }
    
    update() {
        let url = 'formula/getSetupUpdateJson/' + this.props.id;
        $.getJSON(url, this.updateData);
    }
    
    updateGameParams(gameParams) {
        for (const property in gameParams) {
            this.gameParams[property] = gameParams[property];
        }
        this.gameParamsTimeout = setTimeout(this.sendGameParams, this.gameParamsTimeoutMiliseconds);
        console.log(JSON.stringify(gameParams));
        console.log(JSON.stringify(this.gameParams));
    }
    
    sendGameParams() {
        let url = 'formula/editSetup/' + this.props.id;
        let payload = this.gameParams;
        payload["_csrfToken"] = csrfToken;
        $.post(url, this.gameParams, null, 'json')
            .fail(() => this.updateGameParams({}));
    }
    
    render() {
        if (this.state.game == null) {
            return null;
        }
        else {
            return (
                <div id="setup">
                  <h2 id="game-name">{this.state.name}</h2>
                  <div className="row">
                    <div id="player-car-column">
                      <SetupPlayersCarsPanel users={this.state.users} />
                    </div>
                    <div id="setup-column">
                      <SetupGameParamsPanel game={this.state.game}
                          onUpdate={this.updateGameParams}
                          editable={this.state.game.editable} />
                    </div>
                  </div>
                </div>
            );
        }
    }
}

ReactDOM.render(<Setup id={id} />, document.getElementById('root'));
