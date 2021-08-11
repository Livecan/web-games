class GameIndex extends React.Component {
    intervalMs = 5000;
    
    constructor(props) {
        super(props);
        this.state = {};
        this.loadGameList = this.loadGameList.bind(this);
        this.updateGameList = this.updateGameList.bind(this);
        this.loadGameList();
        setInterval(this.loadGameList, this.intervalMs);
    }
    
    updateGameList(data) {
        this.setState({games: data});
        console.log(JSON.stringify(data))
    }
    
    loadGameList() {
        let url = 'formula/index/';
        $.getJSON(url, this.updateGameList);
    }
    
    render = () =>
        <div className="games index content">
          <button className="button float-right">New Game</button>
          <h3>Games</h3>
          <div className="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>Name</th>
                  <th className="actions">Actions</th>
                </tr>
              </thead>
              <tbody>
                {
                  this.state.games?.map(game =>
                    <tr key={game.id}>
                      <td>{game.name}</td>
                      <td className="actions">
                        <a onClick={() => alert(game.id)}>Join</a>
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
