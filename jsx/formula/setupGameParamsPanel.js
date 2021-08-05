export class SetupGameParamsPanel extends React.Component {
    constructor(props) {
        super(props);
        this.handleTrackChoiceChange = this.handleTrackChoiceChange.bind(this);
        this.handleCarsPerPlayerChange = this.handleCarsPerPlayerChange.bind(this);
        this.handleWPAvailableChange = this.handleWPAvailableChange.bind(this);
        this.handleLapsChange = this.handleLapsChange.bind(this);
    }
    
    handleTrackChoiceChange(event) {
        this.props.onUpdate({trackChoice: event.target.value});
    }
    
    handleCarsPerPlayerChange(event) {
        this.props.onUpdate({carPerPlayer: event.target.value});
    }
    
    handleWPAvailableChange(event) {
        this.props.onUpdate({wPAvailable: event.target.value});
    }
    
    handleLapsChange(event) {
        this.props.onUpdate({laps: event.target.value});
    }
    
    render() {
        return (
            <table>
              <tbody>
                <tr>
                  <td>
                    <label htmlFor="track-choice">Track</label>
                  </td>
                  <td>
                    <select name="track-choice" id="track-choice"
                        onChange={this.handleTrackChoiceChange}>
                      <option value="1">Monaco</option>
                      <option value="2">Daytona</option>
                      //TODO: load these option from the server
                    </select>
                  </td>
                </tr>
                <tr>
                  <td colSpan="2">
                    <img src={"img/formula/" + this.props.game.fo_game.fo_track.game_plan}
                        alt={this.props.game.fo_game.fo_track.game_plan} />
                  </td>
                </tr>
                <tr>
                  <td>
                    <label htmlFor="cars-per-player">Cars per player</label>
                  </td>
                  <td>
                    <input type="number" id="cars-per-player"
                        name="cars-per-player" min="1"
                        defaultValue={this.props.game.fo_game.cars_per_player}
                        onChange={this.handleCarsPerPlayerChange} />
                  </td>
                </tr>
                <tr>
                  <td>
                    <label htmlFor="wear-points-available">WP</label>
                  </td>
                  <td>
                    <input type="number" id="wear-points-available"
                        name="wear-points-available" min="6"
                        defaultValue={this.props.game.fo_game.wear_points}
                        onChange={this.handleWPAvailableChange} />
                  </td>
                </tr>
                <tr>
                  <td>
                    <label htmlFor="laps">Laps</label>
                  </td>
                  <td>
                    <input type="number" id="laps" name="laps" min="1"
                        defaultValue={this.props.game.fo_game.laps}
                        onChange={this.handleLapsChange} />
                  </td>
                </tr>
                <tr>
                  <td colSpan="2">
                    <button>Start</button>
                  </td>
                </tr>
              </tbody>
            </table>
        )
    }
}