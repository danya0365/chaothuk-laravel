<x-empty-layout>
    <x-slot name="title">
        {{ __('Messenger') }}
    </x-slot>
    <div x-data="alpineData()" x-init="getConversations();"
        class="h-screen w-full flex antialiased text-green-200 bg-green-900 overflow-hidden">
        <div class="flex-1 flex flex-col">
            <main class="flex-grow flex flex-row min-h-0">
                <section class="flex flex-col flex-auto">
                    @include('messenger.telephone-channel.partials.header')
                    @include('messenger.telephone-channel.partials.conversation')
                    @include('messenger.telephone-channel.partials.footer')
                </section>
            </main>
        </div>
    </div>
    <x-slot name="javascript">
        <script type="text/javascript">
            function alpineData() {
                return {
                    channelId: {{ $messengerChannel->id }},
                    message: '',
                    conversations: [],
                    files: null,
                    participants: {!! $messengerParticipants->toJson() !!},
                    getAvatar: (author) => {
                        let name = author.name;
                        return `https://ui-avatars.com/api/?name=${name ?? 'NO'}&background=0D8ABC&color=fff&size=200`;
                    },
                    dateFormat: (dateString) => {
                        return moment(dateString).calendar(null, {
                            // when the date is closer, specify custom values
                            //lastWeek: '[สัปดาห์ที่แล้ว] dddd, HH:mm',
                            lastDay: '[เมื่อวานนี้], HH:mm',
                            sameDay: '[วันนี้], HH:mm',
                            nextDay: '[พรุ่งนี้], HH:mm',
                            //nextWeek: 'dddd, HH:mm',
                            // when the date is further away, use from-now functionality
                            sameElse: function() {
                                if (moment().isSame(dateString, 'year')) {
                                    return moment(dateString).format('ddd D MMMM, HH:mm');
                                }
                                const dateFormat = 'D MMMM YYYY, HH:mm';
                                const fullDateTime = moment(dateString).format(dateFormat);
                                return '[' + fullDateTime + ']';
                            }
                        });
                    },
                    isCustomer: (participants, userId) => {
                        if (participants.length === 0) return false;
                        const find = participants.find((val) => val.user_id === userId);
                        return find ? find.is_customer : false;
                    },
                    sortConversation: async function() {
                        this.conversations = this.conversations.sort((a, b) => {
                            if (a.id < b.id) {
                                return -1;
                            }
                            if (a.id > b.id) {
                                return 1;
                            }
                            return 0;
                        });

                        setTimeout(function() {
                            $('#messageElement').animate({
                                scrollTop: 999999
                            });
                        }, 200);
                    },
                    getConversations: async function() {
                        const url =
                            '{{ route('ajax.messenger.telephone-channel.conversations', ['channelId' => $messengerChannel->id, 'telephone' => $telephone]) }}';
                        const conversationResponse = await $.get(url)
                        if (conversationResponse.data) {
                            this.conversations = conversationResponse.data
                            this.sortConversation();
                        }
                    },
                    updateConversationSeen: async function(conversation) {
                        if (conversation.seen_at) return;
                        const url = route('ajax.messenger.channel.conversations.seen', {
                            id: this.channelId,
                            conversationId: conversation.id
                        });
                        const updateConversationSeenResponse = await $.post(url)
                    },
                    onSubmit: async function() {
                        let type = '{{ App\Enums\MessengerConversationType::TEXT->value }}'
                        if (this.files) {
                            let formData = new FormData();
                            formData.append('image', this.files[0]);
                            const uploadResponse = await $.ajax({
                                url: '{{ route('ajax.upload.image') }}',
                                type: 'POST',
                                data: formData,
                                async: false,
                                cache: false,
                                contentType: false,
                                enctype: 'multipart/form-data',
                                processData: false,
                            });

                            if (!uploadResponse.data) {
                                return;
                            }

                            const documentUploadUrl = uploadResponse.data.original
                            this.message = documentUploadUrl
                            type = '{{ App\Enums\MessengerConversationType::URL->value }}'
                        }
                        if (this.message == '') return;
                        const url =
                            '{{ route('ajax.messenger.telephone-channel.conversations.store', ['channelId' => $messengerChannel->id, 'telephone' => $telephone]) }}';
                        const conversationResponse = await $.post(url, {
                            content: this.message,
                            local_code_id: new Date().getTime(),
                            type
                        })
                        this.message = ''
                        this.files = null;
                        this.getConversations();
                    },
                }
            }
        </script>
    </x-slot>
</x-empty-layout>
