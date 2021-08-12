class GameIndex extends React.Component {
    intervalMs = 5000;
    redirectDelay = 10;
    
    constructor(props) {
        super(props);
        this.state = {};
        this.getGameList = this.getGameList.bind(this);
        this.updateGameList = this.updateGameList.bind(this);
        this.postCreateNewGame = this.postCreateNewGame.bind(this);
        this.postJoinGame = this.postJoinGame.bind(this);
        this.getGameList();
        setInterval(this.getGameList, this.intervalMs);
    }
    
    updateGameList(data) {
        this.setState({games: data});
        console.log(JSON.stringify(data))
    }
    
    getGameList() {
        let url = 'formula/index/';
        $.getJSON(url, this.updateGameList);
    }
    
    postCreateNewGame() {
        let url = 'formula/create-new-game/';
        let payload = {
            _csrfToken: csrfToken
        };
        $.post(url, payload, (data) => this.redirectWaitingRoom(data.id), "json");
    }
    
    postJoinGame(id) {
        let url = 'formula/join-game/' + id;
        let payload = {
             _csrfToken: csrfToken,
             game_id: id
        };
        $.post(url, payload, this.redirectWaitingRoom(id), "json");
    }
    
    redirectWaitingRoom(id) {
        //delay to avoid unauthorized access because of uncommited transactions on the server
        setTimeout(() => window.location.href = "formula/get-waiting-room/" + id, this.redirectDelay);
    }
    
    render = () =>
        <div className="games index content">
          <button className="button float-right" onClick={this.postCreateNewGame}>New Game</button>
          <h3>Games</h3>
          <div className="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Players</th>
                  <th className="actions">Actions</th>
                </tr>
              </thead>
              <tbody>
                {
                  this.state.games?.map(game =>
                    <tr key={game.id}>
                      <td>{game.name}</td>
                      <td>{game.users.length}</td>
                      <td className="actions">
                        <a onClick={() => this.postJoinGame(game.id)}>Join</a>
                  </td>
                    </tr>
                  )
                }
              </tbody>
            </table>
          </div>
        </div>
    
}

ReactDOM.render(<GameIndex />, document.getElementById('root'));
