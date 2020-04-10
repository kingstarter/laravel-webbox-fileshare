<template>
  <span v-text="timeValue"></span>
</template>

<script>
/**
 * Simple countdown component for counting time down in seconds
 *
 * template: Display remaining duration (or negative value after timeout)
 * props: [ time (millisec), duration (sec), global (bool) ]
 * events: 'timeout' on coundown hitting zero
 *
 * Invokes also global method 'window.Vue.prototype.countdownTimeout()'
 * on timeout if the function has been defined.
 */

  export default {
    props: {
      time: {
        /** Timestamp in milliseconds */
        type: Number,
        default: (new Date()).getTime()
      },
      duration: {
        /** Countdown duration in seconds */
        type: Number,
        default: 60
      },
      global: {
        /**
         * Check for vue-prototype-registered countdownTimeout function
         * and call on timeout if this prop is set
         */
        type: Boolean,
        default: false
      }
    },

    data () {
      return {
        hours: 0,
        minutes: 0,
        seconds: 0,
        ttl: -1
      }
    },

    mounted() {
      // Run countdown timer at start
      this.updateTime()
    },

    watch: {
      ttl(newval, oldval) {
        // Automatically handle timeout if new value is zero
        if (newval === 0)
          this.handleTimeout()
        // Update timer every second
        setTimeout(() => { this.updateTime() }, 1000)
      }
    },

    computed: {
      timeValue () {
        return `${this.ttl < 0 ? '-' : ''}${(this.hours).padZero()}:${(this.minutes).padZero()}:${(this.seconds).padZero()}`
      },
      endtime () {
        return this.time + this.duration * 1000
      },
      clockStart () {
        return new Date(this.time)
      },
      clockEnd () {
        return new Date(this.endtime)
      },
    },

    methods: {
      updateTime() {
        // Get remaining time to life
        this.ttl = Math.floor((this.endtime - (new Date()).getTime()) / 1000)

        // Calculate time values
        let timeRef = { difference: Math.abs(this.ttl) }
        this.hours   = this.calculateTime(timeRef, 3600, 60)
        this.minutes = this.calculateTime(timeRef,   60, 60)
        this.seconds = this.calculateTime(timeRef,    1, 60)
      },
      /**
       * Calculate the number of days, hours, minutes and seconds
       * based on the time-difference (in secnods)
       * between the start-time of the running task and now.
       * @param {Object} timeDifference
       *                  This is an object because then it is passed-by-reference.
       *                  It contains the difference between the start-time of the task and now
       * @param {Number} divisor
       *                  number which the timeDifference is divided by
       *                  to calculate the numberOfTime (days, hours, minutes or seconds)
       * @param {Number} modulo
       *                  number which the timeDifference is modulo-ed by
       *                  to calculate the numberOfTime (days, hours, minutes or seconds)
       * @return {Number} number of times (days, hours, minutes or seconds)
       *                  the running task is running until now
       */
      calculateTime (timeDifference, divisor, modulo) {
        // numberOfTime can be days, hours, minutes or seconds
        const numberOfTime = Math.floor(timeDifference.difference / divisor % modulo)
        const timeInSeconds = numberOfTime * divisor
        timeDifference.difference -= timeInSeconds
        return numberOfTime
      },
      handleTimeout () {
        // Always emit timeout
        this.$emit('timeout')
        if (this.global) {
          // If there is a global countdownTimeout function, call it
          typeof this.countdownTimeout === 'function' && this.countdownTimeout()
        }
      }
    }
  }
</script>
