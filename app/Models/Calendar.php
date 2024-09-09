<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Calendar extends Model
    {
        use HasFactory;

        /**
         * The attributes that are mass assignable.
         *
         * @var array
         */
        protected $fillable = [
            'name',
            'email',
            'visitor_name',
            'visitor_mobile',
            'visitor_email',
            'vehicle_number',
            'vehicle_color',
            'vehicle_model',
            'has_arrival_booking',
            'arrival_mode',
            'arrival_datetime',
            'has_hold_luggage',
            'has_departure_booking',
            'departure_meeting_time',
            'additional_info',
            'google_event_id',
        ];

        /**
         * The attributes that should be cast.
         *
         * @var array
         */
        protected $casts = [
            'arrival_datetime' => 'datetime',
            'departure_meeting_time' => 'datetime',
        ];

        /**
         * Get the formatted start time.
         *
         * @return string
         */
        public function getFormattedStartTimeAttribute(): string
        {
            return $this->start_time->format('Y-m-d H:i:s');
        }

        /**
         * Get the formatted end time.
         *
         * @return string
         */
        public function getFormattedEndTimeAttribute(): string
        {
            return $this->end_time->format('Y-m-d H:i:s');
        }

        /**
         * Scope a query to only include upcoming events.
         *
         * @param  \Illuminate\Database\Eloquent\Builder  $query
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function scopeUpcoming($query): Builder
        {
            return $query->where('start_time', '>=', now());
        }

        /**
         * Scope a query to only include events for a specific date.
         *
         * @param  \Illuminate\Database\Eloquent\Builder  $query
         * @param  string  $date
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function scopeForDate($query, $date): Builder
        {
            return $query->whereDate('start_time', $date);
        }
    }
