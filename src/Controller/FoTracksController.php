<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * FoTracks Controller
 *
 * @property \App\Model\Table\FoTracksTable $FoTracks
 * @method \App\Model\Entity\FoTrack[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FoTracksController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->Authorization->authorize($this->FoTracks);
        $foTracks = $this->paginate($this->FoTracks);

        $this->set(compact('foTracks'));
    }

    /**
     * View method
     *
     * @param string|null $id Fo Track id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $foTrack = $this->FoTracks->get($id, [
            'contain' => ['FoCurves', 'FoPositions', 'FoPositions.FoPosition2PositionsFrom',
                'FoPositions.FoPosition2PositionsFrom.FoPositionTo'],
        ]);
        $this->Authorization->authorize($foTrack);
        $this->set(compact('foTrack'));
    }
}
