<?php

use Livewire\Volt\Component;
use App\Models\Home;
use App\Models\Floor;
use App\Models\Room;
use App\Models\Location;
use App\Models\Measurement;
use Illuminate\Support\Facades\DB;

new class extends Component {
    
    public $currentHomeId;
    
    public function mount()
    {
        $this->currentHomeId = app(\App\Services\HomeContext::class)->getCurrentHomeId();
    }

    public $view = 'floors';
    public $floorId = null;
    public $roomId = null;
    public $locationId = null;

    // Modals/Forms
    public $showAddModal = false;
    public $newName = '';
    public $selectedIcon = 'box';

    // Measurement input
    public $newLabel = '';
    public $newValue = '';

    protected $listeners = ['refresh' => '$refresh'];

    public function selectFloor($id)
    {
        $this->floorId = $id;
        $this->view = 'rooms';
    }

    public function selectRoom($id)
    {
        $this->roomId = $id;
        $this->view = 'locations';
    }

    public function selectLocation($id)
    {
        $this->locationId = $id;
        $this->view = 'detail';
        $this->resetMeasurementInput();
    }

    public function goBack($to)
    {
        $this->view = $to;
        if ($to === 'floors') {
            $this->floorId = null;
            $this->roomId = null;
            $this->locationId = null;
        } elseif ($to === 'rooms') {
            $this->roomId = null;
            $this->locationId = null;
        } elseif ($to === 'locations') {
            $this->locationId = null;
        }
    }

    public function addItem()
    {
        $this->validate([
            'newName' => 'required|min:1',
        ]);

        if (!$this->currentHomeId) {
            $this->addError('newName', '管理する家が選択されていません。先に家を登録してください。');
            return;
        }

        if ($this->view === 'floors') {
            Floor::create(['name' => $this->newName]);
        } elseif ($this->view === 'rooms') {
            Room::create([
                'floor_id' => $this->floorId,
                'name' => $this->newName
            ]);
        } elseif ($this->view === 'locations') {
            Location::create([
                'room_id' => $this->roomId,
                'name' => $this->newName,
                'icon' => $this->selectedIcon
            ]);
        }

        $this->newName = '';
        $this->selectedIcon = 'box';
        $this->showAddModal = false;
    }

    public function addMeasurement()
    {
        $this->validate([
            'newLabel' => 'required',
            'newValue' => 'required|numeric',
        ]);

        Measurement::create([
            'location_id' => $this->locationId,
            'label' => $this->newLabel,
            'value' => $this->newValue,
            'unit' => 'mm',
        ]);

        $this->resetMeasurementInput();
    }

    public function removeMeasurement($id)
    {
        Measurement::destroy($id);
    }

    private function resetMeasurementInput()
    {
        $this->newLabel = '';
        $this->newValue = '';
    }

    public function with(): array
    {
        return [
            'hasHome' => !is_null($this->currentHomeId),
            'floors' => $this->currentHomeId ? Floor::all() : collect(),
            'rooms' => $this->floorId ? Room::where('floor_id', $this->floorId)->get() : [],
            'locations' => $this->roomId ? Location::where('room_id', $this->roomId)->get() : [],
            'currentLocation' => $this->locationId ? Location::with('measurements')->find($this->locationId) : null,
            'breadcrumbs' => $this->getBreadcrumbs(),
        ];
    }

    private function getBreadcrumbs()
    {
        $crumbs = [['label' => '階一覧', 'view' => 'floors']];
        if ($this->floorId) {
            $floor = Floor::find($this->floorId);
            $crumbs[] = ['label' => $floor->name, 'view' => 'rooms'];
        }
        if ($this->roomId) {
            $room = Room::find($this->roomId);
            $crumbs[] = ['label' => $room->name, 'view' => 'locations'];
        }
        if ($this->locationId) {
            $location = Location::find($this->locationId);
            $crumbs[] = ['label' => $location->name, 'view' => 'detail'];
        }
        return $crumbs;
    }

    private function getIconPath($name)
    {
        $icons = [
            'box' => '<path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />',
            'shelf' => '<path d="M3 3h18v18H3V3zm0 6h18M3 15h18M9 3v18M15 3v18" />',
            'cabinet' => '<path d="M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2zm0 6h14M5 15h14M11 3v6M13 3v6M11 9v6M13 9v6M11 15v6M13 15v6" />',
            'room' => '<path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />',
            'closet' => '<path d="M4 4h16v16H4V4zm8 0v16M8 10h.01M16 10h.01" />',
            'window' => '<path d="M4 4h16v16H4V4zm0 8h16M12 4v16" />',
            'appliance' => '<path d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />',
        ];
        return $icons[$name] ?? $icons['box'];
    }
}; ?>

<div class="max-w-md mx-auto p-4 bg-gray-50 min-h-screen pb-20">
    <!-- Breadcrumbs -->
    <nav class="flex mb-6 overflow-x-auto whitespace-nowrap bg-white p-3 rounded-xl shadow-sm border border-gray-100 no-scrollbar">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            @foreach($breadcrumbs as $index => $crumb)
                <li class="inline-flex items-center">
                    @if($index > 0)
                        <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    @endif
                    <button wire:click="goBack('{{ $crumb['view'] }}')" 
                        class="text-sm font-medium {{ $loop->last ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                        {{ $crumb['label'] }}
                    </button>
                </li>
            @endforeach
        </ol>
    </nav>

    @if(!$hasHome)
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-indigo-100/50 p-10 text-center border border-indigo-50">
            <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-[2rem] flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            </div>
            <h2 class="text-2xl font-black text-gray-900 mb-4 tracking-tight">家を登録しましょう</h2>
            <p class="text-gray-500 font-medium mb-8 leading-relaxed">
                寸法データを記録するには、まず管理する「家」を登録する必要があります。
            </p>
            <a href="{{ route('profile') }}" class="inline-block w-full py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 transition-all">
                設定ページで家を作成する
            </a>
        </div>
    @else
        <!-- Content Area -->
        <div class="transition-all duration-300">
        @if($view === 'floors')
            <div class="flex items-center justify-between mb-4 px-1">
                <h2 class="text-xl font-bold text-gray-800">階層を選択</h2>
                <button wire:click="$set('showAddModal', true)" class="text-indigo-600 font-bold p-2 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                    + 追加
                </button>
            </div>
            <div class="grid grid-cols-1 gap-4">
                @forelse($floors as $floor)
                    <button wire:click="selectFloor({{ $floor->id }})" 
                        class="flex items-center justify-between p-5 bg-white rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-300 hover:shadow-md transition-all active:scale-95">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                            <span class="text-lg font-semibold text-gray-700">{{ $floor->name }}</span>
                        </div>
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                @empty
                    <div class="text-center py-10 bg-white rounded-2xl border border-dashed border-gray-300">
                        <p class="text-gray-500">階が登録されていません</p>
                    </div>
                @endforelse
            </div>

        @elseif($view === 'rooms')
            <div class="flex items-center justify-between mb-4 px-1">
                <h2 class="text-xl font-bold text-gray-800">部屋を選択</h2>
                <button wire:click="$set('showAddModal', true)" class="text-indigo-600 font-bold p-2 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                    + 追加
                </button>
            </div>
            <div class="grid grid-cols-1 gap-3">
                @forelse($rooms as $room)
                    <button wire:click="selectRoom({{ $room->id }})" 
                        class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-between hover:bg-gray-50 active:scale-95 transition-all">
                        <span class="text-gray-700 font-medium">{{ $room->name }}</span>
                        <div class="bg-gray-100 px-3 py-1 rounded-full text-xs text-gray-500">
                            {{ $room->locations_count ?? $room->locations()->count() }} 場所
                        </div>
                    </button>
                @empty
                    <p class="text-center text-gray-500 py-10">部屋が見つかりません</p>
                @endforelse
            </div>

        @elseif($view === 'locations')
            <div class="flex items-center justify-between mb-4 px-1">
                <h2 class="text-xl font-bold text-gray-800">場所を選択</h2>
                <button wire:click="$set('showAddModal', true)" class="text-indigo-600 font-bold p-2 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                    + 追加
                </button>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @forelse($locations as $location)
                    <button wire:click="selectLocation({{ $location->id }})" 
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden text-left hover:shadow-md transition-all active:scale-95">
                        <div class="aspect-square bg-gray-100 relative">
                                <div class="w-full h-full flex items-center justify-center text-indigo-400 bg-indigo-50/50">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $this->getIconPath($location->icon) !!}</svg>
                                </div>
                        </div>
                        <div class="p-3">
                            <h3 class="font-bold text-gray-800 text-sm truncate">{{ $location->name }}</h3>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $location->measurements_count ?? $location->measurements()->count() }} items</p>
                        </div>
                    </button>
                @empty
                    <div class="col-span-2 text-center text-gray-500 py-10 font-medium">登録された場所がありません</div>
                @endforelse
            </div>


        @elseif($view === 'detail')
            <div class="space-y-6">
                <!-- Location Image Section -->
            <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden group p-10 flex flex-col items-center justify-center text-indigo-100 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-indigo-50 via-white to-white">
                <svg class="w-20 h-20 mb-4 text-indigo-500/20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">{!! $this->getIconPath($currentLocation->icon) !!}</svg>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">{{ $currentLocation->name }}</h2>
            </div>

                <!-- Measurements List -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-800">計測データ</h3>
                        <span class="bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider">
                            Unit: mm
                        </span>
                    </div>

                    <div class="space-y-4">
                        @foreach($currentLocation->measurements as $m)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase">{{ $m->label }}</p>
                                    <p class="text-xl font-mono font-bold text-gray-800">{{ number_format($m->value) }}<span class="text-sm font-normal text-gray-500 ml-1">mm</span></p>
                                </div>
                                <button onclick="confirm('削除しますか？') || event.stopImmediatePropagation()" wire:click="removeMeasurement({{ $m->id }})" class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <!-- Inline Add Form -->
                    <div class="mt-8 pt-8 border-t border-gray-100">
                        <h4 class="text-sm font-bold text-gray-500 mb-4 px-1">新規計測値を追加</h4>
                        
                        <!-- Quick Select Buttons (Optional helpful feature) -->
                        <div class="flex gap-2 mb-4 overflow-x-auto no-scrollbar py-1">
                            @foreach(['幅', '奥行', '高さ', '径', '厚み'] as $tag)
                                <button type="button" wire:click="$set('newLabel', '{{ $tag }}')" 
                                    class="px-4 py-2 rounded-full text-sm font-medium border @if($newLabel === $tag) bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-200 @else bg-white border-gray-200 text-gray-600 hover:border-indigo-300 @endif transition-all shrink-0">
                                    {{ $tag }}
                                </button>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-12 gap-3 items-end">
                            <div class="col-span-5">
                                <label class="block text-[10px] font-bold text-gray-400 mb-1 ml-1 uppercase">ラベル名</label>
                                <input type="text" wire:model="newLabel" placeholder="例: 幅" 
                                    class="w-full bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-indigo-500 p-3 text-sm">
                            </div>
                            <div class="col-span-5">
                                <label class="block text-[10px] font-bold text-gray-400 mb-1 ml-1 uppercase">値 (mm)</label>
                                <input type="number" step="1" inputmode="numeric" wire:model="newValue" placeholder="0" 
                                    class="w-full bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-indigo-500 p-3 text-sm font-mono font-bold">
                            </div>
                            <div class="col-span-2">
                                <button wire:click="addMeasurement" class="w-full h-[44px] bg-indigo-600 text-white rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 active:scale-95 transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @endif

    <!-- Add Item Modal -->
    @if($showAddModal)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-md" wire:click="$set('showAddModal', false)"></div>
            
            <!-- Modal Box -->
            <div class="relative bg-white rounded-[2rem] w-full max-w-sm shadow-2xl overflow-hidden transform transition-all border border-white/20">
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <h3 class="text-xl font-black text-gray-900">
                            @if($view === 'floors') 階を追加 @elseif($view === 'rooms') 部屋を追加 @else 場所を追加 @endif
                        </h3>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-2 ml-1 uppercase tracking-widest">名前を入力</label>
                            <input type="text" wire:model="newName" placeholder="例: リビング、洗面所など" 
                                class="w-full bg-gray-50 border-gray-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 p-4 text-lg font-bold placeholder:text-gray-300">
                            @error('newName') <span class="text-rose-500 text-xs mt-2 ml-1 block font-bold">{{ $message }}</span> @enderror
                        </div>

                        @if($view === 'locations')
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 mb-3 ml-1 uppercase tracking-widest">アイコンを選択</label>
                            <div class="grid grid-cols-4 gap-3">
                                @foreach(['box', 'shelf', 'cabinet', 'room', 'closet', 'window', 'appliance'] as $iconName)
                                    <button type="button" wire:click="$set('selectedIcon', '{{ $iconName }}')" 
                                        class="aspect-square flex items-center justify-center rounded-2xl border-2 transition-all {{ $selectedIcon === $iconName ? 'border-indigo-600 bg-indigo-50 text-indigo-600 shadow-lg shadow-indigo-100' : 'border-gray-50 bg-gray-50 text-gray-400 hover:border-gray-200' }}">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">{!! $this->getIconPath($iconName) !!}</svg>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="bg-gray-50/50 p-6 flex gap-4 border-t border-gray-100/50">
                    <button wire:click="$set('showAddModal', false)" 
                        class="flex-1 px-4 py-4 text-gray-500 font-black hover:bg-gray-100 rounded-2xl transition-all">
                        閉じる
                    </button>
                    <button wire:click="addItem" 
                        class="flex-1 px-4 py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">
                        保存する
                    </button>
                </div>
            </div>
        </div>
    @endif
    
    <style>
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</div>
