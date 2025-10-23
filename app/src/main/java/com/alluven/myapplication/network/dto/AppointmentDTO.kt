package com.alluven.myapplication.network.dto

data class AppointmentDTO(
    val id: Int,
    val service_name: String,
    val customer_name: String,
    val customer_phone: String?,
    val date: String,
    val time: String
)